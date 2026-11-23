<?php
require_once __DIR__ . '/config.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    if (DB['driver'] === 'mysql') {
        $pdo = new PDO('mysql:host=' . DB['host'] . ';dbname=' . DB['name'] . ';charset=utf8mb4', DB['user'], DB['pass']);
    } else {
        $dir = dirname(DB['sqlite']);
        if (!is_dir($dir)) mkdir($dir, 0775, true);
        $pdo = new PDO('sqlite:' . DB['sqlite']);
        $pdo->exec('PRAGMA foreign_keys = ON');
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    install_if_needed($pdo);
    return $pdo;
}

function q(string $sql, array $p = []): PDOStatement { $s = db()->prepare($sql); $s->execute($p); return $s; }
function rows(string $sql, array $p = []): array { return q($sql, $p)->fetchAll(); }
function row(string $sql, array $p = []) { return q($sql, $p)->fetch(); }
function val(string $sql, array $p = []) { return q($sql, $p)->fetchColumn(); }

/* Contraseña inicial del admin: includes/secret.php (no se sube al repo) o 'demo1234' en local */
function admin_seed_password(): string { return is_file(__DIR__ . '/secret.php') ? (string)(require __DIR__ . '/secret.php') : 'demo1234'; }

/* ---------------- Esquema + datos de ejemplo ---------------- */
function install_if_needed(PDO $pdo): void {
    $my = DB['driver'] === 'mysql';
    $exists = $my
        ? $pdo->query("SHOW TABLES LIKE 'appointments'")->fetchColumn()
        : $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='appointments'")->fetchColumn();
    if ($exists) return;

    $id = $my ? 'INT AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $tail = $my ? ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4' : '';
    $sql = [
        "CREATE TABLE locations (id $id, name VARCHAR(80), address VARCHAR(200), phone VARCHAR(40), active TINYINT DEFAULT 1)$tail",
        "CREATE TABLE hours (id $id, location_id INT, weekday TINYINT, open_time VARCHAR(5), close_time VARCHAR(5))$tail",
        "CREATE TABLE services (id $id, category VARCHAR(60), name VARCHAR(120), duration INT, price DECIMAL(8,2), description TEXT, color VARCHAR(9), active TINYINT DEFAULT 1, sort INT DEFAULT 0)$tail",
        "CREATE TABLE providers (id $id, name VARCHAR(80), title VARCHAR(120), photo VARCHAR(40), color VARCHAR(9), active TINYINT DEFAULT 1)$tail",
        "CREATE TABLE provider_services (provider_id INT, service_id INT)$tail",
        "CREATE TABLE provider_locations (provider_id INT, location_id INT)$tail",
        "CREATE TABLE blocks (id $id, provider_id INT, start_at VARCHAR(19), end_at VARCHAR(19), reason VARCHAR(120))$tail",
        "CREATE TABLE appointments (id $id, code VARCHAR(12), service_id INT, provider_id INT, location_id INT,
            start_at VARCHAR(19), end_at VARCHAR(19), first_name VARCHAR(60), last_name VARCHAR(60), email VARCHAR(120),
            phone VARCHAR(40), dob VARCHAR(10), is_new TINYINT DEFAULT 1, notes TEXT, internal_notes TEXT,
            status VARCHAR(20) DEFAULT 'confirmed', source VARCHAR(20) DEFAULT 'online', created_at VARCHAR(19))$tail",
        "CREATE TABLE leads (id $id, name VARCHAR(120), email VARCHAR(120), phone VARCHAR(40), reason VARCHAR(80),
            location VARCHAR(80), message TEXT, status VARCHAR(20) DEFAULT 'new', created_at VARCHAR(19))$tail",
        "CREATE TABLE users (id $id, name VARCHAR(80), email VARCHAR(120), pass_hash VARCHAR(255), role VARCHAR(20))$tail",
        "CREATE INDEX idx_appt_start ON appointments (start_at)",
    ];
    foreach ($sql as $s) $pdo->exec($s);
    seed($pdo);
}

function seed(PDO $pdo): void {
    $ins = fn($t, $r) => $pdo->prepare("INSERT INTO $t (" . implode(',', array_keys($r)) . ") VALUES (" . rtrim(str_repeat('?,', count($r)), ',') . ")")->execute(array_values($r));

    $ins('locations', ['name' => 'Van Nuys', 'address' => '14540 Victory Blvd, Suite 101, Los Angeles, CA 91411', 'phone' => CLINIC['phone']]);
    $ins('locations', ['name' => 'Encino', 'address' => 'Encino, CA — address coming soon', 'phone' => CLINIC['phone']]);
    foreach ([1, 2] as $loc) {
        foreach ([1, 2, 3, 4, 5] as $d) $ins('hours', ['location_id' => $loc, 'weekday' => $d, 'open_time' => '09:00', 'close_time' => '18:00']);
        $ins('hours', ['location_id' => $loc, 'weekday' => 6, 'open_time' => '09:00', 'close_time' => '14:00']);
    }

    // category, name, minutes, price, color, description
    $services = [
        ['Consultation', 'Free Wellness Consultation', 20, 0, '#9aa58c', 'Meet our team, share your goals and get a personalized plan.'],
        ['Aesthetics', 'Botox / Neurotoxin', 30, 12, '#c9a45c', 'Soften fine lines and wrinkles. Price per unit.'],
        ['Aesthetics', 'Dermal Fillers', 60, 650, '#c9a45c', 'Restore volume to cheeks, lips and jawline.'],
        ['Aesthetics', 'Lip Enhancement', 45, 550, '#c9a45c', 'Natural-looking definition and hydration.'],
        ['Skin & Laser', 'Signature Medical Facial', 60, 165, '#d4b483', 'Deep cleanse, exfoliation, extraction and hydration.'],
        ['Skin & Laser', 'Microneedling', 60, 350, '#d4b483', 'Collagen induction for texture, scars and pores.'],
        ['Skin & Laser', 'Chemical Peel', 45, 175, '#d4b483', 'Brighter, smoother, more even-toned skin.'],
        ['Skin & Laser', 'Laser Hair Removal', 30, 99, '#d4b483', 'Long-lasting smooth skin, any area.'],
        ['IV Therapy', "Myers' Cocktail IV", 45, 185, '#7f9a86', 'Vitamins, minerals and hydration for energy and recovery.'],
        ['IV Therapy', 'Hydration Boost IV', 45, 150, '#7f9a86', 'Rapid rehydration with electrolytes.'],
        ['IV Therapy', 'Immunity IV', 45, 195, '#7f9a86', 'High-dose vitamin C and zinc support.'],
        ['IV Therapy', 'NAD+ Infusion', 120, 450, '#7f9a86', 'Cellular energy, focus and healthy aging.'],
        ['IV Therapy', 'Vitamin B12 Shot', 15, 35, '#7f9a86', 'Quick energy and metabolism support.'],
        ['Weight Management', 'Medical Weight Loss Consultation', 45, 99, '#a3b18a', 'Labs review, body composition and a physician-guided plan.'],
        ['Weight Management', 'Weight Loss Follow-up', 20, 0, '#a3b18a', 'Weekly check-in, progress tracking and adjustments.'],
        ['Primary Care', 'Annual Physical', 45, 0, '#6b7f8e', 'Preventive exam, screenings and lab orders.'],
        ['Primary Care', 'Sick Visit', 30, 0, '#6b7f8e', 'Same-week care for non-emergency illness.'],
        ['Primary Care', 'Lab Work / Blood Draw', 15, 0, '#6b7f8e', 'In-office blood draw. Results reviewed by your provider.'],
    ];
    foreach ($services as $i => [$cat, $name, $dur, $price, $color, $desc]) {
        $ins('services', ['category' => $cat, 'name' => $name, 'duration' => $dur, 'price' => $price, 'description' => $desc, 'color' => $color, 'sort' => $i]);
    }

    // Nombres = placeholders (reemplazar con los nombres reales del equipo)
    $providers = [
        ['Aesthetic Injector', 'RN · Injectables & Skin', 'team1', '#c9a45c', ['Consultation', 'Aesthetics', 'Skin & Laser']],
        ['Medical Director', 'MD / NP · Primary Care & Weight Loss', 'team2', '#6b7f8e', ['Consultation', 'Primary Care', 'Weight Management', 'Aesthetics']],
        ['Skin Specialist', 'Licensed Esthetician · Laser', 'team3', '#d4b483', ['Consultation', 'Skin & Laser', 'IV Therapy']],
        ['IV Nurse', 'LVN · IV Therapy & Wellness', 'team4', '#7f9a86', ['Consultation', 'IV Therapy', 'Weight Management']],
    ];
    foreach ($providers as $i => [$n, $t, $p, $c, $cats]) {
        $ins('providers', ['name' => $n, 'title' => $t, 'photo' => $p, 'color' => $c]);
        $pid = $i + 1;
        $s = $pdo->prepare('SELECT id FROM services WHERE category=?');
        foreach ($cats as $cat) { $s->execute([$cat]); foreach ($s->fetchAll(PDO::FETCH_COLUMN) as $sid) $ins('provider_services', ['provider_id' => $pid, 'service_id' => $sid]); }
        $ins('provider_locations', ['provider_id' => $pid, 'location_id' => 1]);
        if ($pid % 2 === 0 || $pid === 1) $ins('provider_locations', ['provider_id' => $pid, 'location_id' => 2]);
    }

    $ins('users', ['name' => 'Admin Demo', 'email' => 'admin@totalwellness.test', 'pass_hash' => password_hash(admin_seed_password(), PASSWORD_DEFAULT), 'role' => 'admin']);

    // Citas de ejemplo (pacientes ficticios) para que la agenda se vea con vida
    $people = [['Maria', 'Gonzalez'], ['Jessica', 'Lee'], ['Daniel', 'Torres'], ['Ana', 'Ramirez'], ['Sarah', 'Kim'], ['Luis', 'Hernandez'],
               ['Emily', 'Carter'], ['Sofia', 'Martinez'], ['Kevin', 'Nguyen'], ['Paola', 'Diaz'], ['Rachel', 'Adams'], ['Carlos', 'Mendoza']];
    $plan = [ // dayOffset, HH:MM, serviceId, providerId, status
        [0, '09:00', 2, 1, 'completed'], [0, '09:30', 9, 4, 'completed'], [0, '10:00', 16, 2, 'checked_in'], [0, '11:00', 5, 3, 'confirmed'],
        [0, '11:00', 3, 1, 'confirmed'], [0, '12:30', 14, 2, 'confirmed'], [0, '13:00', 12, 4, 'confirmed'], [0, '15:00', 8, 3, 'pending'],
        [0, '16:00', 1, 1, 'pending'], [1, '09:30', 6, 3, 'confirmed'], [1, '10:00', 15, 2, 'confirmed'], [1, '11:00', 10, 4, 'confirmed'],
        [1, '14:00', 4, 1, 'pending'], [2, '10:00', 17, 2, 'confirmed'], [2, '12:00', 11, 4, 'confirmed'], [3, '09:00', 2, 1, 'confirmed'],
        [-1, '10:00', 7, 3, 'completed'], [-1, '11:30', 13, 4, 'no_show'], [-1, '15:00', 14, 2, 'completed'],
    ];
    $durs = $pdo->query('SELECT id, duration FROM services')->fetchAll(PDO::FETCH_KEY_PAIR);
    foreach ($plan as $i => [$off, $hm, $sid, $pid, $st]) {
        // offset en días hábiles (sin domingos) a partir del próximo día abierto
        $day = new DateTime('today');
        while ((int)$day->format('w') === 0) $day->modify('+1 day');
        for ($k = 0; $k < abs($off); $k++) { do { $day->modify(($off > 0 ? '+' : '-') . '1 day'); } while ((int)$day->format('w') === 0); }
        $start = new DateTime($day->format('Y-m-d') . " $hm");
        $end = (clone $start)->modify('+' . $durs[$sid] . ' minutes');
        if ($start > new DateTime() && in_array($st, ['completed', 'checked_in', 'no_show'])) $st = 'confirmed'; // nada "completado" en el futuro
        [$fn, $ln] = $people[$i % count($people)];
        $ins('appointments', [
            'code' => 'TW-' . strtoupper(substr(md5("seed$i"), 0, 6)), 'service_id' => $sid, 'provider_id' => $pid, 'location_id' => 1,
            'start_at' => $start->format('Y-m-d H:i:s'), 'end_at' => $end->format('Y-m-d H:i:s'),
            'first_name' => $fn, 'last_name' => $ln, 'email' => strtolower($fn) . '@example.com', 'phone' => '(818) 555-01' . str_pad((string)($i + 10), 2, '0'),
            'is_new' => $i % 3 === 0 ? 1 : 0, 'notes' => '', 'status' => $st, 'source' => $i % 4 === 0 ? 'phone' : 'online', 'created_at' => date('Y-m-d H:i:s', strtotime("-$i hours")),
        ]);
    }
    $ins('leads', ['name' => 'Valeria Ortiz', 'email' => 'valeria@example.com', 'phone' => '(818) 555-0199', 'reason' => 'Weight Management', 'location' => 'Van Nuys', 'message' => 'Do you accept HSA cards for the weight loss program?', 'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))]);
    $ins('leads', ['name' => 'Michael Brown', 'email' => 'mbrown@example.com', 'phone' => '(818) 555-0177', 'reason' => 'IV Therapy', 'location' => 'Encino', 'message' => 'Group IV session for 4 people on a Saturday?', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))]);
}

/* ---------------- Lógica de disponibilidad ---------------- */

/** Devuelve ['09:00' => [providerIds...], ...] para un servicio/fecha/sucursal */
function available_slots(int $serviceId, string $date, int $locationId, ?int $providerId = null, ?int $ignoreApptId = null): array {
    $svc = row('SELECT * FROM services WHERE id=? AND active=1', [$serviceId]);
    if (!$svc || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) return [];
    $wd = (int)date('w', strtotime($date));
    $h = row('SELECT open_time, close_time FROM hours WHERE location_id=? AND weekday=?', [$locationId, $wd]);
    if (!$h) return [];

    $sqlP = 'SELECT p.id FROM providers p JOIN provider_services ps ON ps.provider_id=p.id AND ps.service_id=?
             JOIN provider_locations pl ON pl.provider_id=p.id AND pl.location_id=? WHERE p.active=1';
    $args = [$serviceId, $locationId];
    if ($providerId) { $sqlP .= ' AND p.id=?'; $args[] = $providerId; }
    $provIds = array_map('intval', array_column(rows($sqlP, $args), 'id'));
    if (!$provIds) return [];

    $busy = [];
    foreach ($provIds as $pid) {
        $busy[$pid] = rows("SELECT start_at s, end_at e FROM appointments WHERE provider_id=? AND status NOT IN ('cancelled','no_show')
                            AND start_at < ? AND end_at > ?" . ($ignoreApptId ? ' AND id<>' . (int)$ignoreApptId : ''),
                           [$pid, "$date 23:59:59", "$date 00:00:00"]);
        $busy[$pid] = array_merge($busy[$pid], rows('SELECT start_at s, end_at e FROM blocks WHERE provider_id=? AND start_at < ? AND end_at > ?',
                           [$pid, "$date 23:59:59", "$date 00:00:00"]));
    }

    $out = [];
    $t = strtotime("$date {$h['open_time']}");
    $close = strtotime("$date {$h['close_time']}");
    $earliest = time() + MIN_NOTICE_MIN * 60;
    $dur = (int)$svc['duration'] * 60;
    for (; $t + $dur <= $close; $t += SLOT_STEP * 60) {
        if ($t < $earliest) continue;
        $s = date('Y-m-d H:i:s', $t); $e = date('Y-m-d H:i:s', $t + $dur);
        foreach ($provIds as $pid) {
            $free = true;
            foreach ($busy[$pid] as $b) if ($b['s'] < $e && $b['e'] > $s) { $free = false; break; }
            if ($free) $out[date('H:i', $t)][] = $pid;
        }
    }
    return $out;
}

/** Crea una cita validando disponibilidad. Devuelve [ok, codigo|error] */
function create_appointment(array $d, string $source = 'online'): array {
    $svc = row('SELECT * FROM services WHERE id=?', [(int)($d['service_id'] ?? 0)]);
    if (!$svc) return [false, 'Service not found'];
    foreach (['first_name', 'last_name', 'phone', 'date', 'time'] as $f) if (empty($d[$f])) return [false, "Missing field: $f"];
    if ($source === 'online' && (empty($d['email']) || !filter_var($d['email'], FILTER_VALIDATE_EMAIL))) return [false, 'Please enter a valid email'];

    $slots = available_slots((int)$svc['id'], $d['date'], (int)$d['location_id'], !empty($d['provider_id']) ? (int)$d['provider_id'] : null);
    if (empty($slots[$d['time']])) return [false, 'Sorry, that time was just taken. Please choose another.'];
    $pid = !empty($d['provider_id']) ? (int)$d['provider_id'] : $slots[$d['time']][0];

    $start = strtotime($d['date'] . ' ' . $d['time']);
    $code = 'TW-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
    q('INSERT INTO appointments (code, service_id, provider_id, location_id, start_at, end_at, first_name, last_name, email, phone, dob, is_new, notes, status, source, created_at)
       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
        $code, $svc['id'], $pid, (int)$d['location_id'], date('Y-m-d H:i:s', $start), date('Y-m-d H:i:s', $start + $svc['duration'] * 60),
        trim($d['first_name']), trim($d['last_name']), trim($d['email'] ?? ''), trim($d['phone']), $d['dob'] ?? '', !empty($d['is_new']) ? 1 : 0,
        trim($d['notes'] ?? ''), $source === 'online' ? 'pending' : 'confirmed', $source, date('Y-m-d H:i:s'),
    ]);
    return [true, $code];
}

const STATUSES = [
    'pending'    => 'Pending',
    'confirmed'  => 'Confirmed',
    'checked_in' => 'Checked in',
    'completed'  => 'Completed',
    'no_show'    => 'No-show',
    'cancelled'  => 'Cancelled',
];
