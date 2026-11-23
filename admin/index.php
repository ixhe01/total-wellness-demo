<?php
require __DIR__ . '/_auth.php';
require_login();
$v = $_GET['v'] ?? 'agenda';
$views = ['agenda' => 'Agenda', 'list' => 'Appointments', 'new' => 'New Appointment', 'leads' => 'Inquiries', 'settings' => 'Settings'];
if (!isset($views[$v])) $v = 'agenda';

$today = date('Y-m-d');
$locations = rows('SELECT * FROM locations ORDER BY id');
$providers = rows('SELECT * FROM providers ORDER BY id');
$services = rows('SELECT * FROM services ORDER BY sort, id');
$newLeads = (int)val("SELECT COUNT(*) FROM leads WHERE status='new'");
$fmt = fn($dt, $f = 'g:i A') => date($f, strtotime($dt));
$badge = fn($s) => '<span class="st st--' . e($s) . '">' . e(STATUSES[$s] ?? $s) . '</span>';
$meta = ['csrf' => $_SESSION['csrf'], 'providers' => array_map(fn($p) => $p + ['photo_url' => img($p['photo'], 200)], $providers),
         'services' => $services, 'locations' => $locations, 'statuses' => STATUSES, 'today' => $today,
         'ps' => rows('SELECT * FROM provider_services'), 'pl' => rows('SELECT * FROM provider_locations')];
?><!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="robots" content="noindex, nofollow"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($views[$v]) ?> · Total Wellness Staff</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css?v=1"><style>:root{--logo:url("<?= e(img('logo_light', 700)) ?>")}</style>
</head>
<body class="v-<?= e($v) ?>">
<aside class="side">
  <a class="side__brand" href="./"><span class="emblem"></span><span><b>TOTAL</b><small>Staff Portal</small></span></a>
  <nav>
    <?php foreach ($views as $k => $label): ?>
      <a href="?v=<?= $k ?>" class="<?= $k === $v ? 'on' : '' ?>"><?= e($label) ?><?php if ($k === 'leads' && $newLeads) echo " <i class=\"pill\">$newLeads</i>"; ?></a>
    <?php endforeach; ?>
  </nav>
  <div class="side__foot">
    <a href="../" target="_blank">View website ↗</a>
    <a href="../book.php" target="_blank">Online booking ↗</a>
    <span><?= e(current_user()['name']) ?> · <a href="logout.php">Sign out</a></span>
  </div>
</aside>

<main class="main">
<?php if ($v === 'agenda'):
    $k = fn($sql, $p = []) => (int)val($sql, $p);
    $kToday = $k("SELECT COUNT(*) FROM appointments WHERE start_at LIKE ? AND status NOT IN ('cancelled')", ["$today%"]);
    $kPend = $k("SELECT COUNT(*) FROM appointments WHERE status='pending' AND start_at >= ?", [date('Y-m-d H:i:s')]);
    $kWeek = $k("SELECT COUNT(*) FROM appointments WHERE start_at BETWEEN ? AND ? AND status NOT IN ('cancelled')", [date('Y-m-d 00:00:00', strtotime('monday this week')), date('Y-m-d 23:59:59', strtotime('sunday this week'))]);
    $kOnline = $k("SELECT COUNT(*) FROM appointments WHERE source='online' AND created_at >= ?", [date('Y-m-d H:i:s', strtotime('-7 days'))]);
?>
  <header class="top">
    <div><h1>Agenda</h1><p class="muted" id="dateLabel"></p></div>
    <div class="top__ctl">
      <select id="locSel"><option value="0">All locations</option><?php foreach ($locations as $l) echo "<option value=\"{$l['id']}\">" . e($l['name']) . '</option>'; ?></select>
      <div class="seg"><button id="prevD" aria-label="Previous day">‹</button><button id="todayD">Today</button><button id="nextD" aria-label="Next day">›</button></div>
      <input type="date" id="dateSel" value="<?= $today ?>">
      <a class="b b--gold" href="?v=new">+ New</a>
    </div>
  </header>
  <section class="kpis">
    <div><b><?= $kToday ?></b><span>Appointments today</span></div>
    <div><b><?= $kPend ?></b><span>Pending confirmation</span></div>
    <div><b><?= $kWeek ?></b><span>This week</span></div>
    <div><b><?= $kOnline ?></b><span>Booked online (7 days)</span></div>
  </section>
  <section class="cal" id="cal"></section>

<?php elseif ($v === 'list'):
    $from = $_GET['from'] ?? $today; $to = $_GET['to'] ?? date('Y-m-d', strtotime('+14 days'));
    $fs = $_GET['status'] ?? ''; $fp = (int)($_GET['provider'] ?? 0); $s = trim($_GET['s'] ?? '');
    $w = ['a.start_at BETWEEN ? AND ?']; $p = ["$from 00:00:00", "$to 23:59:59"];
    if ($fs) { $w[] = 'a.status=?'; $p[] = $fs; }
    if ($fp) { $w[] = 'a.provider_id=?'; $p[] = $fp; }
    if ($s) { $w[] = "(a.first_name LIKE ? OR a.last_name LIKE ? OR a.phone LIKE ? OR a.email LIKE ? OR a.code LIKE ?)"; array_push($p, "%$s%", "%$s%", "%$s%", "%$s%", "%$s%"); }
    $list = rows('SELECT a.*, s.name service, p.name provider, l.name location FROM appointments a JOIN services s ON s.id=a.service_id
                  JOIN providers p ON p.id=a.provider_id JOIN locations l ON l.id=a.location_id WHERE ' . implode(' AND ', $w) . ' ORDER BY a.start_at', $p);
?>
  <header class="top"><div><h1>Appointments</h1><p class="muted"><?= count($list) ?> results</p></div></header>
  <form class="filters">
    <input type="hidden" name="v" value="list">
    <input name="s" placeholder="Search name, phone, email, #" value="<?= e($s) ?>">
    <label>From<input type="date" name="from" value="<?= e($from) ?>"></label><label>To<input type="date" name="to" value="<?= e($to) ?>"></label>
    <select name="status"><option value="">Any status</option><?php foreach (STATUSES as $k2 => $l2) echo "<option value=\"$k2\"" . ($fs === $k2 ? ' selected' : '') . ">$l2</option>"; ?></select>
    <select name="provider"><option value="0">Any provider</option><?php foreach ($providers as $pr) echo "<option value=\"{$pr['id']}\"" . ($fp == $pr['id'] ? ' selected' : '') . '>' . e($pr['name']) . '</option>'; ?></select>
    <button class="b">Filter</button>
  </form>
  <div class="tbl-wrap"><table class="tbl">
    <thead><tr><th>When</th><th>Patient</th><th>Treatment</th><th>Provider</th><th>Location</th><th>Source</th><th>Status</th></tr></thead>
    <tbody>
    <?php foreach ($list as $r): ?>
      <tr data-open="<?= $r['id'] ?>">
        <td><b><?= $fmt($r['start_at'], 'D M j') ?></b><br><span class="muted"><?= $fmt($r['start_at']) ?></span></td>
        <td><b><?= e($r['first_name'] . ' ' . $r['last_name']) ?></b><?= $r['is_new'] ? ' <i class="tag">New</i>' : '' ?><br><span class="muted"><?= e($r['phone']) ?></span></td>
        <td><?= e($r['service']) ?></td><td><?= e($r['provider']) ?></td><td><?= e($r['location']) ?></td>
        <td class="muted"><?= e(ucfirst($r['source'])) ?></td><td><?= $badge($r['status']) ?></td>
      </tr>
    <?php endforeach; if (!$list) echo '<tr><td colspan="7" class="empty">No appointments match these filters.</td></tr>'; ?>
    </tbody></table></div>

<?php elseif ($v === 'new'): ?>
  <header class="top"><div><h1>New appointment</h1><p class="muted">Book a phone or walk-in appointment. Only available times are shown.</p></div></header>
  <form class="card form" id="newForm">
    <div class="grid3">
      <label>Location<select name="location_id" required><?php foreach ($locations as $l) echo "<option value=\"{$l['id']}\">" . e($l['name']) . '</option>'; ?></select></label>
      <label>Treatment<select name="service_id" required><?php $c = ''; foreach ($services as $sv) { if (!$sv['active']) continue; if ($c !== $sv['category']) { echo ($c ? '</optgroup>' : '') . '<optgroup label="' . e($sv['category']) . '">'; $c = $sv['category']; } echo "<option value=\"{$sv['id']}\">" . e($sv['name']) . " ({$sv['duration']} min)</option>"; } echo '</optgroup>'; ?></select></label>
      <label>Provider<select name="provider_id"><option value="">Any available</option></select></label>
    </div>
    <div class="grid3">
      <label>Date<input type="date" name="date" value="<?= $today ?>" min="<?= $today ?>" required></label>
      <label>Source<select name="source"><option value="phone">Phone</option><option value="walk-in">Walk-in</option><option value="instagram">Instagram DM</option><option value="admin">Other</option></select></label>
      <span></span>
    </div>
    <div class="slots" id="newSlots"></div>
    <input type="hidden" name="time" required>
    <div class="grid3">
      <label>First name<input name="first_name" required></label><label>Last name<input name="last_name" required></label><label>Phone<input name="phone" type="tel" required></label>
    </div>
    <div class="grid3">
      <label>Email<input name="email" type="email"></label><label>Date of birth<input name="dob" type="date"></label>
      <label>Patient<select name="is_new"><option value="1">New</option><option value="0">Returning</option></select></label>
    </div>
    <label>Notes<textarea name="notes" rows="2"></textarea></label>
    <div><button class="b b--gold">Create appointment</button> <span class="msg" id="newMsg"></span></div>
  </form>

<?php elseif ($v === 'leads'): $leads = rows('SELECT * FROM leads ORDER BY created_at DESC'); ?>
  <header class="top"><div><h1>Inquiries</h1><p class="muted">Consultation requests from the website form</p></div></header>
  <div class="tbl-wrap"><table class="tbl">
    <thead><tr><th>Received</th><th>Name</th><th>Contact</th><th>Interest</th><th>Message</th><th>Status</th></tr></thead>
    <tbody><?php foreach ($leads as $l): ?>
      <tr><td><?= $fmt($l['created_at'], 'M j, g:i A') ?></td><td><b><?= e($l['name']) ?></b><br><span class="muted"><?= e($l['location']) ?></span></td>
        <td><?= e($l['phone']) ?><br><span class="muted"><?= e($l['email']) ?></span></td><td><?= e($l['reason']) ?></td><td class="msgcell"><?= e($l['message']) ?></td>
        <td><select class="lead-st" data-id="<?= $l['id'] ?>"><?php foreach (['new' => 'New', 'contacted' => 'Contacted', 'booked' => 'Booked', 'closed' => 'Closed'] as $k2 => $l2) echo "<option value=\"$k2\"" . ($l['status'] === $k2 ? ' selected' : '') . ">$l2</option>"; ?></select></td></tr>
    <?php endforeach; if (!$leads) echo '<tr><td colspan="6" class="empty">No inquiries yet.</td></tr>'; ?></tbody>
  </table></div>

<?php else: /* settings */
    $hours = []; foreach (rows('SELECT * FROM hours') as $h) $hours[$h['location_id']][$h['weekday']] = $h;
    $blocks = rows('SELECT b.*, p.name provider FROM blocks b JOIN providers p ON p.id=b.provider_id WHERE end_at >= ? ORDER BY start_at', [date('Y-m-d H:i:s')]);
    $wd = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
?>
  <header class="top"><div><h1>Settings</h1><p class="muted">Treatments, team, hours and time off</p></div></header>

  <section class="card"><h2>Treatments</h2>
    <div class="tbl-wrap"><table class="tbl tbl--edit" id="svcTbl"><thead><tr><th>Category</th><th>Name</th><th>Min</th><th>Price $</th><th>Active</th><th></th></tr></thead><tbody>
    <?php foreach ($services as $sv): ?>
      <tr data-id="<?= $sv['id'] ?>"><td><input name="category" value="<?= e($sv['category']) ?>"></td><td><input name="name" value="<?= e($sv['name']) ?>"><input type="hidden" name="description" value="<?= e($sv['description']) ?>"></td>
      <td><input name="duration" type="number" step="5" value="<?= $sv['duration'] ?>" class="sm"></td><td><input name="price" type="number" value="<?= +$sv['price'] ?>" class="sm"></td>
      <td><input type="checkbox" name="active" <?= $sv['active'] ? 'checked' : '' ?>></td><td><button class="b b--sm save-svc">Save</button></td></tr>
    <?php endforeach; ?>
      <tr data-id=""><td><input name="category" placeholder="Category"></td><td><input name="name" placeholder="New treatment"><input type="hidden" name="description" value=""></td><td><input name="duration" type="number" value="30" class="sm"></td><td><input name="price" type="number" value="0" class="sm"></td><td><input type="checkbox" name="active" checked></td><td><button class="b b--sm b--gold save-svc">Add</button></td></tr>
    </tbody></table></div>
  </section>

  <section class="card"><h2>Team</h2>
    <div class="team-edit">
    <?php foreach ($providers as $pr): $mine = array_column(rows('SELECT service_id FROM provider_services WHERE provider_id=?', [$pr['id']]), 'service_id'); ?>
      <form class="prov" data-id="<?= $pr['id'] ?>">
        <span class="ava" style="background-image:url('<?= e(img($pr['photo'], 200)) ?>')"></span>
        <label>Name<input name="name" value="<?= e($pr['name']) ?>"></label><label>Title<input name="title" value="<?= e($pr['title']) ?>"></label>
        <details><summary>Treatments (<?= count($mine) ?>)</summary><div class="chk">
          <?php foreach ($services as $sv) echo '<label><input type="checkbox" name="services" value="' . $sv['id'] . '"' . (in_array($sv['id'], $mine) ? ' checked' : '') . '> ' . e($sv['name']) . '</label>'; ?>
        </div></details>
        <label class="inline"><input type="checkbox" name="active" <?= $pr['active'] ? 'checked' : '' ?>> Accepts bookings</label>
        <button class="b b--sm">Save</button>
      </form>
    <?php endforeach; ?>
    </div>
  </section>

  <section class="card"><h2>Opening hours</h2>
    <div class="hours-grid">
    <?php foreach ($locations as $l): ?>
      <form class="hours" data-loc="<?= $l['id'] ?>"><h3><?= e($l['name']) ?></h3>
        <?php for ($d = 1; $d <= 7; $d++): $i = $d % 7; $h = $hours[$l['id']][$i] ?? null; ?>
          <div class="hrow" data-wd="<?= $i ?>"><span><?= $wd[$i] ?></span><input type="time" name="open" value="<?= e($h['open_time'] ?? '') ?>"><input type="time" name="close" value="<?= e($h['close_time'] ?? '') ?>"></div>
        <?php endfor; ?>
        <p class="muted small">Leave empty = closed</p><button class="b b--sm">Save hours</button>
      </form>
    <?php endforeach; ?>
    </div>
  </section>

  <section class="card"><h2>Time off &amp; blocked time</h2>
    <form class="grid4" id="blockForm">
      <label>Provider<select name="provider_id"><?php foreach ($providers as $pr) echo "<option value=\"{$pr['id']}\">" . e($pr['name']) . '</option>'; ?></select></label>
      <label>From<input type="datetime-local" name="start" required></label><label>To<input type="datetime-local" name="end" required></label>
      <label>Reason<input name="reason" placeholder="Vacation, training, lunch…"></label>
      <button class="b b--gold">Block time</button>
    </form>
    <ul class="blocks"><?php foreach ($blocks as $b): ?><li><b><?= e($b['provider']) ?></b> · <?= $fmt($b['start_at'], 'M j g:i A') ?> → <?= $fmt($b['end_at'], 'M j g:i A') ?> <span class="muted"><?= e($b['reason']) ?></span> <button class="b b--sm del-block" data-id="<?= $b['id'] ?>">Remove</button></li><?php endforeach; if (!$blocks) echo '<li class="muted">No upcoming blocked time.</li>'; ?></ul>
  </section>
<?php endif; ?>
</main>

<!-- Panel lateral de cita -->
<div class="drawer" id="drawer" aria-hidden="true"><div class="drawer__bg" data-close></div><div class="drawer__panel" role="dialog" aria-label="Appointment"><button class="drawer__x" data-close aria-label="Close">×</button><div id="drawerBody"></div></div></div>

<script>window.TW = <?= json_encode($meta) ?>;</script>
<script src="admin.js?v=1"></script>
</body></html>
