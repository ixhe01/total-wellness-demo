# Total Wellness Clinic — sitio de muestra + sistema de citas

## Correr en local (Windows)
1. Doble clic en `iniciar-local.bat`. Si no tienes PHP, la ventana te dice cómo instalarlo (`winget install PHP.PHP.8.3`).
2. Se abre http://localhost:8000
   - Sitio público: `/`
   - Reserva online: `/book.php`
   - Panel interno: `/admin` → **admin@totalwellness.test / demo1234**

La base de datos (SQLite) se crea sola en `data/` con datos de ejemplo. Para reiniciar la demo, borra `data/twc.sqlite`.

## Qué incluye
- **Sitio público** (estructura tipo Sculpt DTLA): hero, 3 pilares, bienvenida, 6 categorías de servicios, franja de reserva, statement, tratamientos destacados, equipo, membresías, Instagram, testimonios, ubicaciones, formulario de consulta y footer.
- **Reserva online en 5 pasos**: sucursal → tratamiento → proveedor (o "cualquiera") → fecha y hora (solo horarios libres reales) → datos del paciente. Evita doble reservación.
- **Panel interno**: agenda por proveedor (día), KPIs, ficha de cita (confirmar, check-in, completar, no-show, cancelar, notas internas, reprogramar), lista con filtros y búsqueda, alta de citas por teléfono/walk-in, consultas del formulario web, y configuración (tratamientos, equipo y qué servicios da cada quien, horarios por sucursal, vacaciones/bloqueos).

## Personalizar
- Datos de la clínica (teléfono, correo): `includes/config.php`
- Fotos: se usan las del álbum de Google Fotos y de Unsplash (solo ejemplo). Para usar archivos propios, pon en `img/` un archivo con el nombre de la clave: `group.jpg`, `duo.jpg`, `team1.jpg`…`team4.jpg`, `logo_light.png`, `iv.jpg`, `injectables.jpg`, `facial.jpg`, `weight.jpg`, `primary.jpg`, `wellness.jpg`, `lobby.jpg`, `laser.jpg`, `skin.jpg`.
- Nombres del equipo, precios, testimonios y dirección de Encino son **placeholders**.

## Publicar en hosting (cPanel / HostGator)
1. Sube todo el contenido de esta carpeta a `public_html` (o a una subcarpeta, p. ej. `/demo`). Funciona en raíz o subcarpeta.
2. Requiere PHP 8.1+. SQLite funciona tal cual; si prefieres MySQL, crea la BD en cPanel y en `includes/config.php` cambia `'driver' => 'mysql'` y los datos de acceso. Las tablas se crean solas.
3. Cambia la contraseña del admin antes de compartir el link.

## Antes de usarlo con pacientes reales
Esto es una demo. Con datos reales de pacientes en EE. UU. aplica HIPAA: hosting con BAA, HTTPS, contraseñas por usuario, bitácora de accesos y respaldos. Pendiente también: correos/SMS de confirmación y recordatorio (Twilio / SendGrid o n8n).
