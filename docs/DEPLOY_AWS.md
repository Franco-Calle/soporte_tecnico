# Despliegue en AWS EC2 (Free Tier)

Guia paso a paso para desplegar el sistema en una instancia EC2 t2.micro o t3.micro (elegible para la capa gratuita durante 12 meses). MySQL corre en la misma instancia dentro de Docker; no se usa RDS.

## Resumen de arquitectura

```
Internet  ->  Elastic IP  ->  EC2 t3.micro (Amazon Linux 2023)
                                 |
                                 +-- Docker
                                     +-- nginx  (puerto 80 publico)
                                     +-- app    (PHP-FPM 8.3)
                                     +-- mysql  (solo red interna)
                                     +-- queue  (worker Laravel)
```

Todo lo que se ejecuta en la instancia corre en contenedores. La instancia solo necesita Docker.

---

## 1. Crear la instancia EC2

1. Consola AWS -> **EC2** -> **Launch instance**.
2. **Name:** `soporte-tecnico-prod`.
3. **AMI:** *Amazon Linux 2023* (arquitectura `x86_64`). Free tier elegible.
4. **Instance type:** `t3.micro` (o `t2.micro` si `t3.micro` no aparece como free tier en tu region). 1 vCPU, 1 GB RAM.
5. **Key pair:** crea uno nuevo (`soporte-tecnico-key`) y descarga el `.pem`. Guardalo con permisos `chmod 400`.
6. **Network settings** -> **Edit**:
   - VPC/subnet: los que vienen por defecto.
   - Auto-assign public IP: **Enable**.
   - Firewall (security group) -> **Create security group**:
     - Nombre: `soporte-tecnico-sg`.
     - Reglas de entrada:
       | Tipo   | Protocolo | Puerto | Origen           | Descripcion             |
       |--------|-----------|--------|------------------|-------------------------|
       | SSH    | TCP       | 22     | Mi IP            | Acceso administrativo   |
       | HTTP   | TCP       | 80     | 0.0.0.0/0        | Trafico web publico     |
       | HTTPS  | TCP       | 443    | 0.0.0.0/0        | Solo si vas a poner SSL |
7. **Storage:** 20 GB gp3 (free tier permite hasta 30 GB).
8. **Launch instance**.
9. Espera a que el estado sea *Running*. Anota la **Public IPv4 address**.

### Asignar Elastic IP (opcional pero recomendado)

Sin Elastic IP, la IP publica cambia cada vez que reinicias la instancia.

1. Consola EC2 -> **Elastic IPs** -> **Allocate Elastic IP address** -> **Allocate**.
2. Selecciona la IP recien creada -> **Actions** -> **Associate Elastic IP** -> elige la instancia.

> **Costo:** una Elastic IP es gratuita mientras este *asociada* a una instancia en ejecucion. Si la desasocias o la instancia esta detenida, AWS cobra ~$0.005/hora.

---

## 2. Conectar por SSH

Desde tu maquina local (NixOS):

```bash
ssh -i /ruta/a/soporte-tecnico-key.pem ec2-user@<IP_PUBLICA>
```

---

## 3. Preparar el sistema operativo

Ejecuta esto en la instancia EC2 tras conectar por SSH.

```bash
sudo dnf update -y
sudo dnf install -y git
```

### Crear swap file (critico con 1 GB de RAM)

Sin swap, la instancia se cuelga al construir imagenes Docker o al compilar assets con Node.

```bash
sudo dd if=/dev/zero of=/swapfile bs=1M count=2048
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
free -h
```

Deberia mostrar 2.0 GB de swap.

### Instalar Docker + docker compose

```bash
sudo dnf install -y docker
sudo systemctl enable --now docker
sudo usermod -aG docker ec2-user

# docker compose v2 como plugin
DOCKER_CONFIG=${DOCKER_CONFIG:-/usr/local/lib/docker}
sudo mkdir -p "$DOCKER_CONFIG/cli-plugins"
sudo curl -SL https://github.com/docker/compose/releases/latest/download/docker-compose-linux-x86_64 \
    -o "$DOCKER_CONFIG/cli-plugins/docker-compose"
sudo chmod +x "$DOCKER_CONFIG/cli-plugins/docker-compose"
```

Cierra la sesion SSH y vuelve a entrar (para que el grupo `docker` haga efecto).

```bash
exit
ssh -i /ruta/a/soporte-tecnico-key.pem ec2-user@<IP_PUBLICA>
docker --version
docker compose version
```

---

## 4. Clonar el repositorio

En la instancia:

```bash
cd ~
git clone <URL_DEL_REPO> soporte_tecnico
cd soporte_tecnico
```

Si el repo es privado, genera una clave SSH en la instancia (`ssh-keygen`) y anadela como deploy key en el proveedor Git.

---

## 5. Configurar el `.env` de produccion

```bash
cp .env.production.example .env
nano .env
```

Edita como minimo:

- `APP_URL=http://<IP_PUBLICA>` (o el dominio, si usas Route53).
- `DB_PASSWORD=<algo-fuerte>`.
- `DB_ROOT_PASSWORD=<algo-fuerte-distinto>`.

Guarda con `Ctrl+O`, `Enter`, `Ctrl+X`.

---

## 6. Primer despliegue

```bash
./deploy.sh
```

El script hace, en orden:

1. Verifica que `.env` existe.
2. `git pull` (idempotente si no hay cambios).
3. Construye la imagen `soporte-tecnico/app:prod` con `Dockerfile.prod`.
4. Levanta MySQL y espera a que este healthy.
5. Ejecuta `composer install --no-dev --optimize-autoloader`.
6. Compila los assets con Vite en un contenedor Node efimero.
7. `key:generate`, `migrate --force`, `config:cache`, `route:cache`, `view:cache`.
8. Levanta `app`, `nginx` y `queue`.

Al terminar, prueba:

```bash
curl -I http://localhost/
# Debe responder 200 OK o 302 (redireccion al login).
```

Desde tu navegador local: `http://<IP_PUBLICA>/`.

### Sembrar datos de demostracion (opcional)

```bash
docker compose -f docker-compose.prod.yml run --rm app php artisan db:seed --force
```

Usuarios creados: `admin@taller.local` / `password`, `tecnico@taller.local` / `password`. **Cambialos** desde `/usuarios` tras el primer login.

---

## 7. Actualizaciones posteriores

Cada vez que quieras desplegar cambios:

```bash
cd ~/soporte_tecnico
./deploy.sh
```

Es idempotente: hace `git pull`, reconstruye si cambio el Dockerfile, aplica migraciones nuevas, y recarga cache.

---

## 8. Backups de MySQL

Backup manual:

```bash
docker compose -f docker-compose.prod.yml exec -T mysql \
    mysqldump -uroot -p"$(grep DB_ROOT_PASSWORD .env | cut -d= -f2)" soporte_tecnico \
    | gzip > ~/backups/soporte_$(date +%F_%H%M).sql.gz
```

Backup automatico diario con cron:

```bash
mkdir -p ~/backups
crontab -e
# Anade esta linea:
# 0 3 * * * cd /home/ec2-user/soporte_tecnico && docker compose -f docker-compose.prod.yml exec -T mysql mysqldump -uroot -p"$(grep DB_ROOT_PASSWORD .env | cut -d= -f2)" soporte_tecnico | gzip > /home/ec2-user/backups/soporte_$(date +\%F).sql.gz
```

Considera copiar los backups a un bucket S3 con `aws s3 cp` (S3 tambien tiene free tier).

---

## 9. Dominio propio y HTTPS (opcional)

Si quieres un dominio con SSL gratuito via Let's Encrypt:

1. Compra un dominio (Route53, Namecheap, etc.).
2. Crea un registro A que apunte al Elastic IP.
3. Instala Certbot en la instancia y usa el companion `nginx-proxy-manager` o `certbot` con nginx en el host.

Alternativa mas simple: pon un CloudFront delante con certificado ACM (tambien tiene tier gratuito), o Cloudflare como proxy inverso (gratis, tarda 5 minutos y da SSL sin cambiar la instancia).

---

## 10. Diagnostico

### Logs

```bash
docker compose -f docker-compose.prod.yml logs -f app
docker compose -f docker-compose.prod.yml logs -f nginx
docker compose -f docker-compose.prod.yml logs -f queue
docker compose -f docker-compose.prod.yml logs -f mysql
```

### Estado

```bash
docker compose -f docker-compose.prod.yml ps
docker stats --no-stream    # uso de RAM/CPU en tiempo real
```

### Reiniciar un servicio

```bash
docker compose -f docker-compose.prod.yml restart nginx
```

### Limpiar espacio en disco

```bash
docker system prune -af --volumes    # cuidado: borra volumenes no usados
```

### Errores comunes

- **"Cannot allocate memory" al hacer `npm run build`**: verifica que el swap este activo (`free -h`). Si no, repite el paso de swap.
- **502 Bad Gateway en el navegador**: `docker compose logs app`; probablemente falto `key:generate` o el `.env` esta mal.
- **MySQL no arranca**: revisa `logs mysql`. Suele ser un password vacio en `.env`.

---

## 11. Costos estimados (post free tier)

Cuando se acabe el ano de free tier:

| Recurso                | Costo mensual estimado (us-east-1) |
|------------------------|------------------------------------|
| t3.micro on-demand     | ~$7.50                             |
| 20 GB gp3              | ~$1.60                             |
| Elastic IP (asociada)  | $0                                 |
| Transferencia salida   | 100 GB gratis, luego ~$0.09/GB     |
| **Total tipico**       | **~$10/mes**                       |

Con Reserved Instance a 1 ano bajas a ~$5/mes.
