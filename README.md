# MVP Soporte Tecnico & Ventas

Sistema agil para un taller de reparacion de PCs, Laptops y Celulares y venta de repuestos.
Stack: **Laravel 11 + MySQL 8 + Tailwind CSS** sobre **Docker** (pensado para NixOS host).

Cubre los requerimientos del PDF `CALLE CASTILLO_soporte_tecnico.pdf`:

| Requerimiento | Como se cumple |
|---|---|
| RF-01 Catalogo hibrido | `catalogo_items` con `tipo` (servicio/bien) y filtro por `categoria_equipo` (escritorio/laptop/celular). |
| RF-02 Recepcion y OT | Modelos `Cliente` + `Equipo` + `OrdenTrabajo`; cruce servicios+bienes via `orden_items`. |
| RF-03 Inventario | Panel `/inventario`, movimientos entrada/salida/ajuste, alerta cuando `stock <= stock_minimo`. |
| RF-04 Consulta publica por DNI | Ruta publica `/consulta`, linea de tiempo con 6 estados. |
| RF-05 Facturacion + caja | Liquidacion automatica al pasar a "Listo para Entrega"; `/caja` suma efectivo, Yape, Plin, Transferencia. |
| RNF-01 Indices | Indices en `clientes.dni` y `ordenes_trabajo.numero_ot`. |
| RNF-02 Mobile-first | Layout Tailwind responsive (sidebar apilado en mobile). |
| RNF-03 Stack liviano | Laravel monolitico + MySQL, sin Redis ni cloud. |
| RNF-04 Datos protegidos | Consulta publica solo expone nombre, modelo, estado y monto pendiente. Password de desbloqueo cifrada (Laravel Crypt). |

## Roles

- `admin`: gestiona catalogo, usuarios y todo lo demas.
- `tecnico`: registra OTs, actualiza estados, cobra pagos, ajusta inventario.

## Arranque (100 % Docker, valido en NixOS)

**Prerequisito**: Docker + Docker Compose en el host. Nada mas.

```bash
# 1. Copiar variables de entorno
cp .env.example .env

# 2. Levantar servicios (app PHP-FPM, nginx, mysql, node/vite, queue worker)
docker compose up -d --build

# 3. Instalar dependencias PHP (dentro del contenedor)
docker compose exec app composer install

# 4. Generar clave de aplicacion
docker compose exec app php artisan key:generate

# 5. Migrar y sembrar datos demo
docker compose exec app php artisan migrate --seed

# 6. Compilar assets (el contenedor 'node' ya corre `vite dev`; para produccion:)
docker compose exec node npm run build
```

La app queda disponible en **http://localhost:8080**.
Vite (dev) escucha en http://localhost:5173.
MySQL expone el puerto **3307** al host para conectar clientes GUI si lo necesitas.

### Credenciales de demo

| Rol | Correo | Contrasena |
|---|---|---|
| Admin | `admin@taller.local` | `password` |
| Tecnico | `tecnico@taller.local` | `password` |

### DNIs de prueba para la consulta publica

- `12345678` — Juan Perez (Laptop en reparacion, saldo pendiente)
- `87654321` — Maria Lopez (Celular en diagnostico)
- `11223344` — Carlos Diaz (Escritorio entregado)

## Comandos habituales (siempre dentro del contenedor)

```bash
# Correr tests
docker compose exec app php artisan test

# Migraciones frescas
docker compose exec app php artisan migrate:fresh --seed

# Tinker (REPL)
docker compose exec app php artisan tinker

# Ver logs de la cola
docker compose logs -f queue
```

## Estructura destacada

```
app/
  Enums/                   -> EstadoOrden, TipoEquipo, TipoItem, MetodoPago, Rol
  Http/
    Controllers/           -> Dashboard, ConsultaPublica, OrdenTrabajo, ...
    Middleware/            -> EnsureUserHasRole (alias `role:admin`)
    Requests/              -> Form Requests dedicados (validacion fuera del controller)
  Models/                  -> User, Cliente, Equipo, OrdenTrabajo, CatalogoItem, ...
database/
  migrations/              -> Incluye indices RNF-01
  seeders/DatabaseSeeder   -> Datos demo con OTs en varios estados
  factories/               -> Uno por modelo
resources/
  views/
    layouts/               -> app (interno) y public (consulta DNI)
    consulta/              -> RF-04 + RNF-04
    ordenes/               -> RF-02, RF-05
    catalogo/              -> RF-01
    inventario/            -> RF-03
    caja/                  -> RF-05 cierre diario
    tickets/               -> Comprobante interno imprimible (HTML + PDF)
tests/Feature/             -> Tests con RefreshDatabase para flujos criticos
docker/
  php/Dockerfile           -> PHP 8.3-fpm-alpine + extensiones
  nginx/default.conf       -> Reverse proxy a php-fpm
docker-compose.yml         -> app, nginx, mysql, node, queue
tailwind.config.js         -> Paleta obligatoria como custom colors
```

## Paleta de colores (Tailwind custom)

- `primario` `#0A0D40` — sidebar, headers, texto de alto contraste
- `secundario` `#2C4A73` — botones primarios, navegacion activa
- `acento` `#4B8CA6` — hover, focus rings
- `exito` `#A0F2AC` — badges positivos (entregado)
- `fondo-suave` `#D0F2D3` — tarjetas y fondos secundarios

Uso: `class="bg-primario text-white"`, `class="btn-primary"` (componente Tailwind ya definido en `resources/css/app.css`).

## Notas

- No hay notificaciones al cliente (SMS/email) en el MVP: el cliente consulta su estado por RF-04.
- El comprobante que emite el sistema es interno (no factura electronica SUNAT).
- La cola `queue:work` corre como servicio Docker; hoy no procesa jobs, queda lista si mas adelante se agregan.
- La contrasena de desbloqueo del equipo se guarda con `Crypt::encryptString` y solo se muestra a usuarios autenticados en la ficha interna de la OT (nunca en la consulta publica).
