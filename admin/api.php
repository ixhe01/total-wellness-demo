<?php
require __DIR__ . '/_auth.php';
header('Content-Type: application/json; charset=utf-8');
require_login(true);

function out($d, int $c = 200) { http_response_code($c); echo json_encode($d); exit; }
$a = $_GET['action'] ?? '';
$in = json_decode(file_get_contents('php://input'), true) ?: $_GET;
if ($_SERVER['REQUEST_METHOD'] === 'POST') check_csrf();

$apptSql = 'SELECT a.*, s.name service, s.color, s.duration, p.name provider, l.name location FROM appointments a
            JOIN services s ON s.id=a.service_id JOIN providers p ON p.id=a.provider_id JOIN locations l ON l.id=a.location_id';

try {
    switch ($a) {
        case 'day': // citas de un día para la agenda
            $d = $in['date'] ?? date('Y-m-d'); $loc = (int)($in['location_id'] ?? 0);
            $list = rows("$apptSql WHERE a.start_at >= ? AND a.start_at <= ?" . ($loc ? " AND a.location_id=$loc" : '') . ' ORDER BY a.start_at',
                         [$d . ' 00:00:00', $d . ' 23:59:59']);
            $blocks = rows('SELECT * FROM blocks WHERE start_at < ? AND end_at > ?', [$d . ' 23:59:59', $d . ' 00:00:00']);
            out(['appointments' => $list, 'blocks' => $blocks]);

        case 'get':
            out(row("$apptSql WHERE a.id=?", [(int)$in['id']]) ?: ['error' => 'Not found']);

        case 'status':
            if (!isset(STATUSES[$in['status'] ?? ''])) out(['error' => 'Bad status'], 422);
            q('UPDATE appointments SET status=? WHERE id=?', [$in['status'], (int)$in['id']]); out(['ok' => true]);

        case 'notes':
            q('UPDATE appointments SET internal_notes=? WHERE id=?', [trim($in['notes'] ?? ''), (int)$in['id']]); out(['ok' => true]);

        case 'slots':
            $s = available_slots((int)$in['service_id'], $in['date'], (int)$in['location_id'], !empty($in['provider_id']) ? (int)$in['provider_id'] : null, !empty($in['ignore']) ? (int)$in['ignore'] : null);
            out(array_keys($s));

        case 'reschedule':
            $ap = row('SELECT * FROM appointments WHERE id=?', [(int)$in['id']]);
            $svc = row('SELECT * FROM services WHERE id=?', [$ap['service_id']]);
            $pid = (int)($in['provider_id'] ?: $ap['provider_id']);
            $slots = available_slots((int)$svc['id'], $in['date'], (int)$ap['location_id'], $pid, (int)$ap['id']);
            if (empty($slots[$in['time']]) && empty($in['force'])) out(['error' => 'That time is not available for this provider.'], 409);
            $st = strtotime($in['date'] . ' ' . $in['time']);
            q('UPDATE appointments SET start_at=?, end_at=?, provider_id=? WHERE id=?', [date('Y-m-d H:i:s', $st), date('Y-m-d H:i:s', $st + $svc['duration'] * 60), $pid, $ap['id']]);
            out(['ok' => true]);

        case 'create':
            [$ok, $res] = create_appointment($in, $in['source'] ?? 'phone');
            if (!$ok) out(['error' => $res], 409);
            out(['ok' => true, 'code' => $res]);

        case 'lead_status':
            q('UPDATE leads SET status=? WHERE id=?', [$in['status'], (int)$in['id']]); out(['ok' => true]);

        case 'save_service':
            $f = [$in['category'], $in['name'], (int)$in['duration'], (float)$in['price'], $in['description'] ?? '', !empty($in['active']) ? 1 : 0];
            if (!empty($in['id'])) q('UPDATE services SET category=?, name=?, duration=?, price=?, description=?, active=? WHERE id=?', [...$f, (int)$in['id']]);
            else q('INSERT INTO services (category,name,duration,price,description,active,color,sort) VALUES (?,?,?,?,?,?,?,99)', [...$f, '#b48a45']);
            out(['ok' => true]);

        case 'save_provider':
            q('UPDATE providers SET name=?, title=?, active=? WHERE id=?', [$in['name'], $in['title'], !empty($in['active']) ? 1 : 0, (int)$in['id']]);
            q('DELETE FROM provider_services WHERE provider_id=?', [(int)$in['id']]);
            foreach ((array)($in['services'] ?? []) as $sid) q('INSERT INTO provider_services (provider_id, service_id) VALUES (?,?)', [(int)$in['id'], (int)$sid]);
            out(['ok' => true]);

        case 'save_hours':
            q('DELETE FROM hours WHERE location_id=?', [(int)$in['location_id']]);
            foreach ($in['days'] as $wd => $h) if (!empty($h['open']) && !empty($h['close']))
                q('INSERT INTO hours (location_id, weekday, open_time, close_time) VALUES (?,?,?,?)', [(int)$in['location_id'], (int)$wd, $h['open'], $h['close']]);
            out(['ok' => true]);

        case 'add_block':
            q('INSERT INTO blocks (provider_id, start_at, end_at, reason) VALUES (?,?,?,?)', [(int)$in['provider_id'], str_replace('T', ' ', $in['start']) . ':00', str_replace('T', ' ', $in['end']) . ':00', $in['reason'] ?? '']);
            out(['ok' => true]);

        case 'del_block':
            q('DELETE FROM blocks WHERE id=?', [(int)$in['id']]); out(['ok' => true]);

        default: out(['error' => 'Unknown action'], 404);
    }
} catch (Throwable $t) { out(['error' => 'Server error: ' . $t->getMessage()], 500); }
