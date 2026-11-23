<?php
/* API pública del sistema de citas (JSON) */
require __DIR__ . '/includes/db.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');            // permite el formulario desde la página de muestra
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

function out($data, int $code = 200) { http_response_code($code); echo json_encode($data); exit; }

$action = $_GET['action'] ?? '';
$in = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? (json_decode(file_get_contents('php://input'), true) ?: $_POST)
    : $_GET;

try {
    switch ($action) {
        case 'meta':
            out([
                'locations' => rows('SELECT id, name, address FROM locations WHERE active=1'),
                'services'  => rows('SELECT id, category, name, duration, price, description FROM services WHERE active=1 ORDER BY sort, id'),
                'providers' => array_map(function ($p) {
                    $p['photo'] = img($p['photo'], 400);
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
            if (!$ok) out(['error' => $res], 409);
            $a = row('SELECT a.*, s.name service, p.name provider, l.name location, l.address FROM appointments a
                      JOIN services s ON s.id=a.service_id JOIN providers p ON p.id=a.provider_id JOIN locations l ON l.id=a.location_id WHERE code=?', [$res]);
            out(['ok' => true, 'appointment' => [
                'code' => $a['code'], 'service' => $a['service'], 'provider' => $a['provider'], 'location' => $a['location'], 'address' => $a['address'],
                'when' => date('l, F j, Y \a\t g:i A', strtotime($a['start_at'])), 'name' => $a['first_name'],
            ]]);

        case 'lead':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') out(['error' => 'POST only'], 405);
            if (!empty($in['website'])) out(['ok' => true]);
            if (empty($in['name']) || (empty($in['email']) && empty($in['phone']))) out(['error' => 'Please add your name and a way to contact you.'], 422);
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
