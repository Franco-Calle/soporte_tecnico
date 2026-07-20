# Guion de Exposición
## Sistema de Gestión para Taller de Soporte Técnico y Venta de Repuestos

**Duración estimada:** 15 – 20 minutos  
**Presentador:** Franco Calle Castillo  
**Tipo:** Exposición académica / sustentación de proyecto

---

## ESTRUCTURA DE LA PRESENTACIÓN

| # | Sección | Tiempo estimado |
|---|---------|-----------------|
| 1 | Saludo e introducción | 1 min |
| 2 | Planteamiento del problema | 2 min |
| 3 | Descripción de la solución | 2 min |
| 4 | Tecnologías utilizadas | 1 min |
| 5 | Demostración en vivo | 7 min |
| 6 | Arquitectura del sistema | 2 min |
| 7 | Conclusiones y preguntas | 2 min |

---

## 1. SALUDO E INTRODUCCIÓN *(~1 minuto)*

> *"Buenos días / buenas tardes. Mi nombre es Franco Calle Castillo y el día de hoy voy a presentarles el proyecto que desarrollé como parte del curso: un Sistema de Gestión para Taller de Soporte Técnico y Venta de Repuestos."*

> *"Este sistema nació de una necesidad real: los talleres de reparación de computadoras y celulares en el Perú trabajan en su mayoría con cuadernos, hojas de cálculo o en el mejor caso con sistemas genéricos que no se adaptan a su flujo de trabajo. Eso genera desorden, pérdida de información y una atención al cliente deficiente."*

---

## 2. PLANTEAMIENTO DEL PROBLEMA *(~2 minutos)*

> *"Vamos a imaginar la siguiente situación: un cliente llega al taller con su laptop que no enciende. El técnico la recibe, escribe los datos del cliente en un cuaderno, pega un papelito en la laptop y la guarda en el almacén."*

> *"Tres días después el cliente llama para preguntar cómo va su equipo. El técnico tiene que buscar el cuaderno, encontrar el nombre, recordar en qué parte del taller está la laptop... y si hay un repuesto de por medio, tiene que ir a revisar físicamente el inventario."*

> *"Ese proceso tiene varios problemas críticos:"*

**[Mostrar lista en diapositiva o pizarra]**

- **Sin trazabilidad**: no hay historial del estado del equipo en cada momento
- **Sin control de inventario**: no se sabe cuándo un repuesto se agota hasta que falta
- **Sin consulta para el cliente**: el cliente depende de llamar al técnico para saber el estado
- **Sin registro de pagos**: no se lleva un cierre de caja claro por día
- **Sin seguridad**: datos como la contraseña de desbloqueo del equipo quedan expuestos

---

## 3. DESCRIPCIÓN DE LA SOLUCIÓN *(~2 minutos)*

> *"La solución que desarrollé es un sistema web completo que digitaliza todo ese flujo. Tiene dos grandes módulos:"*

**Módulo 1 – Gestión interna (para administrador y técnico)**

- Registro de clientes y equipos con todos sus datos
- Órdenes de trabajo con línea de tiempo de estados
- Catálogo de servicios y repuestos con control de stock
- Registro de pagos y cierre de caja diario
- Panel de control con métricas en tiempo real

**Módulo 2 – Consulta pública (para el cliente final)**

- Sin login: el cliente ingresa su DNI y número de orden
- Ve el estado actual de su equipo y la línea de tiempo
- No se expone ningún dato sensible (sin teléfono, dirección, ni contraseña)

> *"El sistema implementa roles de acceso: el Administrador tiene control total, y el Técnico puede gestionar órdenes e inventario pero no puede administrar usuarios ni el catálogo."*

---

## 4. TECNOLOGÍAS UTILIZADAS *(~1 minuto)*

> *"Para el desarrollo usé el siguiente stack tecnológico:"*

| Capa | Tecnología |
|------|-----------|
| Backend | PHP 8.3 + Laravel 11 |
| Base de datos | MySQL 8.4 |
| Frontend | Blade + Tailwind CSS |
| Servidor web | Nginx 1.27 |
| Contenerización | Docker + Docker Compose |
| Despliegue | AWS EC2 (t3.micro, free tier) |

> *"Todo el sistema corre en contenedores Docker, lo que garantiza que funciona igual en cualquier entorno: en mi laptop de desarrollo y en el servidor de producción en AWS."*

---

## 5. DEMOSTRACIÓN EN VIVO *(~7 minutos)*

> *"Vamos a ver el sistema funcionando. Tengo el sistema desplegado en AWS y lo vamos a recorrer siguiendo el flujo de trabajo real de un taller."*

### 5.1 – Ingreso al sistema *(30 seg)*

> *"Accedemos a la URL del sistema. El sistema nos pide credenciales. Voy a ingresar como Administrador."*

- Ingresar a `http://[IP]/login`
- Usuario: `admin@taller.local` / contraseña: `password`
- **[Mostrar el dashboard]**

> *"Esto es el panel de control. De un vistazo veo: cuántas órdenes están pendientes hoy, el desglose por estado, las alertas de stock bajo, y el ingreso del día."*

### 5.2 – Registrar un nuevo cliente y equipo *(1 min)*

> *"Vamos a simular que llega un cliente nuevo. Voy a Clientes → Nuevo cliente."*

- Crear cliente con DNI, nombre, teléfono, dirección
- Luego crear una orden de trabajo desde el cliente

> *"Noten que cuando registro el equipo, tengo un campo especial para la contraseña de desbloqueo. Ese dato se guarda cifrado en la base de datos — ni el técnico ni el administrador pueden verlo en texto claro desde la interfaz, solo cuando atienden al equipo directamente."*

### 5.3 – Gestionar la orden de trabajo *(2 min)*

> *"Una vez creada la orden, vemos la vista detalle. Aquí está la línea de tiempo de estados: Recibido → En Diagnóstico → Esperando Repuesto → En Reparación → Listo para Entrega → Entregado."*

- Cambiar estado a "En Diagnóstico", agregar diagnóstico
- Agregar un ítem al trabajo (servicio de pantalla + repuesto)
- Mostrar cómo el stock del repuesto disminuye automáticamente

> *"Cuando agrego un repuesto a la orden, el sistema descuenta automáticamente el stock del inventario y registra el movimiento. No hay que hacerlo manualmente."*

### 5.4 – Alerta de stock bajo *(30 seg)*

- Ir a Inventario
- Mostrar los items marcados con alerta roja (stock ≤ stock mínimo)

> *"El sistema alerta cuando un repuesto llega al stock mínimo configurado. También aparece en el dashboard. Así el administrador puede hacer pedidos antes de que falte el material."*

### 5.5 – Registrar pago y ver caja *(1 min)*

- Desde la orden, registrar un pago parcial en efectivo
- Cambiar estado a "Listo para Entrega"
- Ir a Caja → mostrar el cierre del día con totales por método de pago

> *"La sección de Caja muestra un resumen diario: cuánto entró por Efectivo, Yape, Plin y Transferencia. Es el cierre de caja del día sin necesidad de calcularlo manualmente."*

### 5.6 – Generar ticket / comprobante *(30 seg)*

- Desde la orden, generar e imprimir el comprobante de servicio

> *"El sistema genera un comprobante de servicio imprimible con el detalle de trabajos y pagos. Se puede imprimir directamente desde el navegador."*

### 5.7 – Consulta pública del cliente *(1 min)*

- Abrir pestaña nueva / modo incógnito
- Ir a `http://[IP]/` (página de inicio pública)
- Ingresar DNI `12345678` o número de OT `OT-000001`
- Mostrar el resultado sin datos sensibles

> *"Esta es la parte que más valoran los clientes: pueden consultar el estado de su equipo desde su celular, sin llamar al taller, sin tener usuario ni contraseña. Solo necesitan su DNI."*

---

## 6. ARQUITECTURA DEL SISTEMA *(~2 minutos)*

> *"Ahora les explico brevemente cómo está construido el sistema por dentro."*

**[Mostrar o dibujar el diagrama de arquitectura]**

```
Cliente web                    EC2 t3.micro (AWS)
(navegador)   ──HTTP──►   [Nginx :80]
                               │
                          [PHP-FPM :9000]  ◄──► [MySQL]
                               │
                          [Queue Worker]   ──►  [jobs en BD]
```

> *"Todo corre en Docker Compose dentro de una instancia EC2 de AWS en la capa gratuita. Nginx recibe las peticiones web y las pasa a PHP-FPM que ejecuta el código Laravel. MySQL guarda los datos en un volumen persistente. Y hay un worker separado para procesar trabajos en segundo plano."*

> *"El código sigue el patrón MVC de Laravel: los controladores reciben las peticiones, los modelos interactúan con la base de datos, y las vistas Blade renderizan el HTML con estilos Tailwind."*

> *"Para la seguridad, implementé: cifrado de datos sensibles, control de acceso por roles (RBAC), protección CSRF de Laravel, y sanitización de datos de entrada con Form Requests dedicados."*

---

## 7. CONCLUSIONES *(~2 minutos)*

> *"Para cerrar, quisiera destacar los puntos más importantes de este proyecto:"*

**Lo que se logró:**

- Un sistema completo y funcional, desplegado en la nube y accesible desde cualquier dispositivo
- Cubre el flujo completo del taller: desde que el cliente llega hasta que recoge su equipo y paga
- Control de inventario automático integrado al flujo de trabajo
- Consulta pública sin exposición de datos sensibles
- Todo desplegado en AWS con costo de infraestructura de $0 durante el primer año (free tier)

**Lo que aprendí:**

- Arquitectura de software con patrones MVC y RBAC
- Contenerización con Docker para garantizar reproducibilidad
- Despliegue en la nube con AWS EC2
- Importancia del diseño centrado en el usuario real (el técnico de taller)

> *"Este sistema puede ser adoptado por cualquier taller de soporte técnico en el Perú con cero costo de infraestructura durante el primer año. La inversión está en el desarrollo, no en el hardware."*

> *"Eso es todo de mi parte. Quedo abierto a sus preguntas. Muchas gracias."*

---

## PREGUNTAS FRECUENTES EN SUSTENTACIONES

Prepárate para responder:

**¿Por qué Laravel y no otro framework?**
> Laravel es el framework PHP más usado en el ecosistema peruano de desarrollo web, tiene documentación excelente, ecosistema maduro, y es ideal para proyectos con CRUD intensivo como este. Su ORM Eloquent simplifica el manejo de la base de datos y los Form Requests garantizan validación centralizada.

**¿Por qué Docker?**
> Docker garantiza que el entorno de desarrollo y el de producción son idénticos. El mismo `docker compose up` que uso en mi laptop es el que corre en el servidor AWS. Elimina el clásico problema de "en mi máquina funciona".

**¿Por qué no usaste RDS (MySQL gestionado de AWS)?**
> Para un taller pequeño, el costo de RDS (~$15-30/mes) no se justifica. MySQL dentro del mismo contenedor Docker, con backups diarios automáticos, es suficiente para el volumen de datos de un taller. Migrar a RDS en el futuro es un cambio de 5 minutos en el `.env`.

**¿Cómo se protegen las contraseñas de desbloqueo de los equipos?**
> Se usa cifrado simétrico AES-256 (el cifrado de Laravel con `Crypt::encryptString`). La clave de cifrado es la `APP_KEY` del archivo `.env`, que nunca se sube al repositorio. Sin esa clave, los valores en la base de datos son ilegibles.

**¿Qué pasaría si se cae el servidor?**
> La instancia EC2 tiene `restart: unless-stopped` en todos los contenedores Docker, así que si se reinicia la máquina, todos los servicios vuelven solos. Los datos están en un volumen Docker persistente. El backup diario automatizado garantiza que en el peor caso se pierde un día de datos.

**¿Podría escalar el sistema para múltiples sedes?**
> Sí. El diseño de la base de datos soporta agregar una tabla `sedes` y asociar órdenes, usuarios e inventario a una sede. La arquitectura Docker facilita desplegar instancias adicionales detrás de un balanceador de carga.
