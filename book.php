<?php
require __DIR__ . '/includes/db.php';
$page_title = t('Book Online', 'Agenda en línea') . ' | ' . CLINIC['name'];
$body_class = 'page-book';
$extra_js = ['booking.js'];
$promo = preg_replace('/[^A-Z0-9]/', '', strtoupper($_GET['promo'] ?? ''));
include __DIR__ . '/includes/header.php';
$BT = [
    'any' => t('Any available', 'Cualquiera disponible'), 'earliest' => t('Earliest opening', 'El primer horario libre'),
    'from' => t('from', 'desde'), 'free' => t('Complimentary / insurance', 'Cortesía / seguro'),
    'loadingDays' => t('Loading availability…', 'Cargando disponibilidad…'), 'loadingTimes' => t('Loading times…', 'Cargando horarios…'),
    'noDays' => t('No availability in the next weeks. Please call us.', 'Sin disponibilidad en las próximas semanas. Llámanos.'),
    'noTimes' => t('No times left on this day.', 'Ya no hay horarios este día.'), 'morning' => t('Morning', 'Mañana'), 'afternoon' => t('Afternoon', 'Tarde'),
    'booking' => t('Booking…', 'Agendando…'), 'loadErr' => t('Could not load booking data.', 'No se pudo cargar la agenda.'),
    'code' => t('Confirmation #', 'Confirmación #'), 'treatment' => t('Treatment', 'Tratamiento'), 'with' => t('With', 'Con'),
    'when' => t('When', 'Cuándo'), 'where' => t('Where', 'Dónde'), 'promo' => t('Promo', 'Promoción'), 'locale' => t('en-US', 'es-MX'),
];
?>
<script>window.TW_BT=<?= json_encode($BT) ?>;window.TW_PROMO=<?= json_encode($promo) ?>;</script>
<section class="book-hero">
  <div class="wrap">
    <p class="eyebrow"><?= t('Online Booking', 'Agenda en línea') ?></p>
    <h1><?= t('Book your appointment', 'Agenda tu cita') ?></h1>
    <p class="muted"><?= t('Pick a treatment, a provider and a time that works for you. You’ll get a confirmation right away.', 'Elige tratamiento, especialista y el horario que te acomode. Recibirás tu confirmación al instante.') ?></p>
    <?php if ($promo): ?><p class="promo-badge">🎁 <?= t('Offer applied:', 'Promoción aplicada:') ?> <strong><?= e($promo) ?></strong> — <?= t('$25 off your first IV + free B12 shot', '$25 de descuento en tu primer IV + B12 gratis') ?></p><?php endif; ?>
  </div>
</section>

<section class="booking">
  <div class="wrap booking__grid">
    <div class="booking__main">
      <ol class="steps" id="steps">
        <li data-s="1" class="is-on"><?= t('Location', 'Sucursal') ?></li><li data-s="2"><?= t('Treatment', 'Tratamiento') ?></li><li data-s="3"><?= t('Provider', 'Especialista') ?></li><li data-s="4"><?= t('Date &amp; Time', 'Fecha y hora') ?></li><li data-s="5"><?= t('Your Info', 'Tus datos') ?></li>
      </ol>

      <div class="panel is-on" data-panel="1">
        <h2><?= t('Where would you like to visit?', '¿A qué sucursal quieres ir?') ?></h2>
        <div class="opts opts--2" id="locList"></div>
      </div>

      <div class="panel" data-panel="2">
        <h2><?= t('Choose a treatment', 'Elige un tratamiento') ?></h2>
        <div class="cat-tabs" id="catTabs"></div>
        <div class="svc-list" id="svcList"></div>
      </div>

      <div class="panel" data-panel="3">
        <h2><?= t('Who would you like to see?', '¿Con quién te gustaría atenderte?') ?></h2>
        <div class="opts opts--prov" id="provList"></div>
      </div>

      <div class="panel" data-panel="4">
        <h2><?= t('Pick a date &amp; time', 'Elige fecha y hora') ?></h2>
        <div class="days" id="dayList"></div>
        <div class="slots" id="slotList"><p class="muted"><?= t('Select a date to see available times.', 'Selecciona una fecha para ver horarios.') ?></p></div>
      </div>

      <div class="panel" data-panel="5">
        <h2><?= t('Your information', 'Tus datos') ?></h2>
        <form class="form" id="bookForm" novalidate>
          <div class="form__row"><label><?= t('First name', 'Nombre') ?>*<input name="first_name" required autocomplete="given-name"></label><label><?= t('Last name', 'Apellido') ?>*<input name="last_name" required autocomplete="family-name"></label></div>
          <div class="form__row"><label><?= t('Email', 'Correo') ?>*<input name="email" type="email" required autocomplete="email" inputmode="email"></label><label><?= t('Mobile phone', 'Celular') ?>*<input name="phone" type="tel" required autocomplete="tel" inputmode="tel"></label></div>
          <div class="form__row"><label><?= t('Date of birth', 'Fecha de nacimiento') ?><input name="dob" type="date"></label>
            <label><?= t('Have you visited us before?', '¿Ya nos has visitado?') ?><select name="is_new"><option value="1"><?= t('No, I’m a new patient', 'No, soy paciente nuevo') ?></option><option value="0"><?= t('Yes, returning patient', 'Sí, ya soy paciente') ?></option></select></label></div>
          <label><?= t('Anything we should know? (optional)', '¿Algo que debamos saber? (opcional)') ?><textarea name="notes" rows="3" placeholder="<?= t('Goals, questions, preferred language…', 'Metas, preguntas, idioma preferido…') ?>"></textarea></label>
          <label class="check"><input type="checkbox" name="consent" required> <?= t('I agree to receive appointment reminders by text and email, and to the 24-hour cancellation policy.', 'Acepto recibir recordatorios por mensaje y correo, y la política de cancelación con 24 horas de anticipación.') ?></label>
          <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
          <button class="btn btn--gold btn--block" type="submit"><?= t('Confirm Appointment', 'Confirmar cita') ?></button>
          <p class="form__msg" role="status"></p>
        </form>
      </div>

      <div class="panel" data-panel="6">
        <div class="done">
          <span class="emblem emblem--xl" aria-hidden="true"></span>
          <h2><?= t('You’re booked, ', '¡Listo, ') ?><span id="doneName"></span>!</h2>
          <p class="muted"><?= t('We sent a confirmation to your email. Your request will be confirmed by our team shortly.', 'Te enviamos la confirmación a tu correo. Nuestro equipo confirmará tu cita en breve.') ?></p>
          <dl class="done__dl" id="doneDl"></dl>
          <div class="feature__btns" style="justify-content:center"><a class="btn btn--dark" href="./"><?= t('Back to home', 'Volver al inicio') ?></a><a class="btn btn--line" href="book.php"><?= t('Book another', 'Agendar otra') ?></a></div>
        </div>
      </div>

      <div class="nav-steps"><button class="btn btn--line" id="backBtn" type="button" hidden>← <?= t('Back', 'Atrás') ?></button></div>
    </div>

    <aside class="summary" id="summary">
      <h3><?= t('Your appointment', 'Tu cita') ?></h3>
      <dl>
        <dt><?= t('Location', 'Sucursal') ?></dt><dd data-k="location">—</dd>
        <dt><?= t('Treatment', 'Tratamiento') ?></dt><dd data-k="service">—</dd>
        <dt><?= t('Provider', 'Especialista') ?></dt><dd data-k="provider">—</dd>
        <dt><?= t('Date &amp; time', 'Fecha y hora') ?></dt><dd data-k="when">—</dd>
      </dl>
      <p class="summary__note"><?= t('Questions? Call', '¿Dudas? Llama al') ?> <a href="tel:<?= CLINIC['phone_raw'] ?>"><?= CLINIC['phone'] ?></a></p>
    </aside>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
