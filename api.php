<?php
/* API pública del sistema de citas (JSON) */
require __DIR__ . '/includes/db.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');            // permite el formulario desde la página de muestra
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

function fmt_when(string $dt): string {
    $ts = strtotime($dt);
    if (lang() !== 'es') return date('l, F j, Y \a\t g:i A', $ts);
    $d = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
    $m = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    return ucfirst($d[date('w', $ts)]) . ' ' . date('j', $ts) . ' de ' . $m[date('n', $ts) - 1] . ' de ' . date('Y', $ts) . ', ' . date('H:i', $ts) . ' h';
}
function out($data, int $code = 200) { http_response_code($code); echo json_encode($data); exit; }

$action = $_GET['action'] ?? '';
$in = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? (json_decode(file_get_contents('php://input'), true) ?: $_POST)
    : $_GET;

try {
    switch ($action) {
        case 'meta':
            out([
                'locations' => array_map(fn($l) => ['address' => tr($l['address'])] + $l, rows('SELECT id, name, address FROM locations WHERE active=1')),
                'services'  => array_map(fn($s) => ['category' => tr($s['category']), 'name' => tr($s['name']), 'description' => tr((string)$s['description'])] + $s,
                                         rows('SELECT id, category, name, duration, price, description FROM services WHERE active=1 ORDER BY sort, id')),
                'providers' => array_map(function ($p) {
                    $p['photo'] = img($p['photo'], 400); $p['name'] = tr($p['name']); $p['title'] = tr($p['title']);
                    $p['services'] = array_map('intval', array_column(rows('SELECT service_id FROM provider_services WHERE provider_id=?', [$p['id']]), 'service_id'));
                    $p['locations'] = array_map('intval', array_column(rows('SELECT location_id FROM provider_locations WHERE provider_id=?', [$p['id']]), 'location_id'));
                    return $p;
                }, rows('SELECT id, name, title, photo FROM providers WHERE active=1')),
            ]);

        case 'days': // disponibilidad resumida por día
            $sid = (int)$in['service_id']; $loc = (int)$in['location_id']; $pid = !empty($in['provider_id']) ? (int)$in['provider_id'] : null;
            $days = [];
            for ($i = 0; $i < BOOK_DAYS; $i++) {
                $d = date('Y-m-d', strtotime("today +$i day"));
                $days[] = ['date' => $d, 'count' => count(available_slots($sid, $d, $loc, $pid))];
            }
            out($days);

        case 'slots':
            $slots = available_slots((int)$in['service_id'], $in['date'] ?? '', (int)$in['location_id'], !empty($in['provider_id']) ? (int)$in['provider_id'] : null);
            out(array_keys($slots));

        case 'book':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(['error' => 'POST only'], 405);
            if (!empty($in['website'])) out(['error' => 'Spam'], 400); // honeypot
            [$ok, $res] = create_appointment($in, 'online');
            if (!$ok) out(['error' => lang() === 'es' ? (['Please enter a valid email' => 'Escribe un correo válido', 'Sorry, that time was just taken. Please choose another.' => 'Ese horario se acaba de ocupar. Elige otro, por favor.'][$res] ?? 'Revisa tus datos: ' . $res) : $res], 409);
            $a = row('SELECT a.*, s.name service, p.name provider, l.name location, l.address FROM appointments a
                      JOIN services s ON s.id=a.service_id JOIN providers p ON p.id=a.provider_id JOIN locations l ON l.id=a.location_id WHERE code=?', [$res]);
            out(['ok' => true, 'appointment' => [
                'code' => $a['code'], 'service' => tr($a['service']), 'provider' => tr($a['provider']), 'location' => $a['location'], 'address' => tr($a['address']),
                'when' => fmt_when($a['start_at']), 'name' => $a['first_name'],
            ]]);

        case 'lead':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(['error' => 'POST only'], 405);
            if (!empty($in['website'])) out(['ok' => true]);
            if (empty($in['name']) || (empty($in['email']) && empty($in['phone']))) out(['error' => t('Please add your name and a way to contact you.', 'Agrega tu nombre y un teléfono o correo.')], 422);
            q('INSERT INTO leads (name,email,phone,reason,location,message,status,created_at) VALUES (?,?,?,?,?,?,?,?)', [
                trim($in['name']), trim($in['email'] ?? ''), trim($in['phone'] ?? ''), $in['reason'] ?? '', $in['location'] ?? '', trim($in['message'] ?? ''), 'new', date('Y-m-d H:i:s'),
            ]);
            out(['ok' => true]);

        default:
            out(['error' => 'Unknown action'], 404);
    }
} catch (Throwable $t) {
    out(['error' => 'Server error', 'detail' => $t->getMessage()], 500);
}
