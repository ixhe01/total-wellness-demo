<?php
/* ==========================================================
   TOTAL WELLNESS CLINIC | LA — Configuración general (DEMO)
   Edita aquí datos de la clínica, base de datos e imágenes.
   ========================================================== */

date_default_timezone_set('America/Los_Angeles');

const CLINIC = [
    'name'      => 'Total Wellness Clinic',
    'short'     => 'Total Wellness',
    'tagline'   => 'Look Better · Feel Better · Live Better',
    'phone'     => '(818) 555-0123',          // TODO: teléfono real
    'phone_raw' => '+18185550123',
    'email'     => 'hello@totalwellnessclinicla.com', // TODO: correo real
    'instagram' => 'totalwellnessclinicla',
];

/* ---------- Base de datos ----------
   Local / demo: SQLite (no requiere instalar nada).
   Hosting (HostGator, etc.): cambia 'driver' a 'mysql' y llena los datos. */
const DB = [
    'driver' => 'sqlite',                       // 'sqlite' | 'mysql'
    'sqlite' => __DIR__ . '/../data/twc.sqlite',
    'host'   => 'localhost',
    'name'   => 'twc_demo',
    'user'   => 'root',
    'pass'   => '',
];

/* Si el sitio público vive en otro dominio/carpeta que el sistema de citas,
   pon aquí la URL del sistema (con / al final). Vacío = mismo lugar. */
const BOOKING_URL = '';

/* Slot de agenda en minutos y días hacia adelante que se pueden reservar */
const SLOT_STEP   = 15;
const BOOK_DAYS   = 30;
const MIN_NOTICE_MIN = 60;   // no se puede reservar con menos de 1 h de anticipación

/* ---------- Imágenes ----------
   Si existe /img/<clave>.jpg se usa el archivo local; si no, la foto del álbum.
   Las de stock (Unsplash) son solo de ejemplo. */
function img(string $key, int $w = 1200): string {
    static $album = null;
    if ($album === null) $album = require __DIR__ . '/album.php';
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
        if (is_file(__DIR__ . "/../img/$key.$ext")) return base_url() . "img/$key.$ext";
    }
    if (isset($album[$key])) return $album[$key] . "=w$w";
    $stock = [
        'hero'       => '1555820585-c5ae44394b79',
        'iv'         => '1633219664572-473fd988a44f',
        'injectables'=> '1746708810803-722593e53772',
        'facial'     => '1570172619644-dfd03ed5d881',
        'laser'      => '1700760933574-9f0f4ea9aa3b',
        'primary'    => '1631217868264-e5b90bb7e133',
        'weight'     => '1567013514336-6de53c9e7e63',
        'skin'       => '1713085085470-fba013d67e65',
        'wellness'   => '1544367567-0f2fcb009e0b',
        'lobby'      => '1762625570087-6d98fca29531',
        'glow'       => '1551184451-76b762941ad6',
    ];
    if (isset($stock[$key])) return "https://images.unsplash.com/photo-{$stock[$key]}?auto=format&fit=crop&w=$w&q=75";
    return '';
}

function base_url(): string {
    // Funciona en raíz o en subcarpeta (ej. dominio.com/demo/)
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');
    $dir = rtrim(dirname($script), '/');
    if (str_ends_with($dir, '/admin')) $dir = substr($dir, 0, -6);
    return ($dir === '' ? '' : $dir) . '/';
}

function app_url(string $path = ''): string { return (BOOKING_URL !== '' ? BOOKING_URL : base_url()) . $path; }

function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
