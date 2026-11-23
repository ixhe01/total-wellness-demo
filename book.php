<?php
require __DIR__ . '/includes/db.php';
$page_title = 'Book Online | ' . CLINIC['name'];
$body_class = 'page-book';
$extra_js = ['booking.js'];
include __DIR__ . '/includes/header.php';
?>
<section class="book-hero">
  <div class="wrap">
    <p class="eyebrow">Online Booking</p>
    <h1>Book your appointment</h1>
    <p class="muted">Pick a treatment, a provider and a time that works for you. You’ll get a confirmation right away.</p>
  </div>
</section>

<section class="booking">
  <div class="wrap booking__grid">
    <div class="booking__main">
      <ol class="steps" id="steps">
        <li data-s="1" class="is-on">Location</li><li data-s="2">Treatment</li><li data-s="3">Provider</li><li data-s="4">Date &amp; Time</li><li data-s="5">Your Info</li>
      </ol>

      <div class="panel is-on" data-panel="1">
        <h2>Where would you like to visit?</h2>
        <div class="opts opts--2" id="locList"></div>
      </div>

      <div class="panel" data-panel="2">
        <h2>Choose a treatment</h2>
        <div class="cat-tabs" id="catTabs"></div>
        <div class="svc-list" id="svcList"></div>
      </div>

      <div class="panel" data-panel="3">
        <h2>Who would you like to see?</h2>
        <div class="opts opts--prov" id="provList"></div>
      </div>

      <div class="panel" data-panel="4">
        <h2>Pick a date &amp; time</h2>
        <div class="days" id="dayList"></div>
        <div class="slots" id="slotList"><p class="muted">Select a date to see available times.</p></div>
      </div>

      <div class="panel" data-panel="5">
        <h2>Your information</h2>
        <form class="form" id="bookForm" novalidate>
          <div class="form__row"><label>First name*<input name="first_name" required autocomplete="given-name"></label><label>Last name*<input name="last_name" required autocomplete="family-name"></label></div>
          <div class="form__row"><label>Email*<input name="email" type="email" required autocomplete="email"></label><label>Mobile phone*<input name="phone" type="tel" required autocomplete="tel"></label></div>
          <div class="form__row"><label>Date of birth<input name="dob" type="date"></label>
            <label>Have you visited us before?<select name="is_new"><option value="1">No, I’m a new patient</option><option value="0">Yes, returning patient</option></select></label></div>
          <label>Anything we should know? (optional)<textarea name="notes" rows="3" placeholder="Goals, questions, preferred language…"></textarea></label>
          <label class="check"><input type="checkbox" name="consent" required> I agree to receive appointment reminders by text and email, and to the 24-hour cancellation policy.</label>
          <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">
          <button class="btn btn--gold btn--block" type="submit">Confirm Appointment</button>
          <p class="form__msg" role="status"></p>
        </form>
      </div>

      <div class="panel" data-panel="6">
        <div class="done">
          <span class="emblem emblem--xl" aria-hidden="true"></span>
          <h2>You’re booked, <span id="doneName"></span>!</h2>
          <p class="muted">We sent a confirmation to your email. Your request will be confirmed by our team shortly.</p>
          <dl class="done__dl" id="doneDl"></dl>
          <div class="feature__btns" style="justify-content:center"><a class="btn btn--dark" href="./">Back to home</a><a class="btn btn--line" href="book.php">Book another</a></div>
        </div>
      </div>

      <div class="nav-steps"><button class="btn btn--line" id="backBtn" type="button" hidden>← Back</button></div>
    </div>

    <aside class="summary" id="summary">
      <h3>Your appointment</h3>
      <dl>
        <dt>Location</dt><dd data-k="location">—</dd>
        <dt>Treatment</dt><dd data-k="service">—</dd>
        <dt>Provider</dt><dd data-k="provider">—</dd>
        <dt>Date &amp; time</dt><dd data-k="when">—</dd>
      </dl>
      <p class="summary__note">Questions? Call <a href="tel:<?= CLINIC['phone_raw'] ?>"><?= CLINIC['phone'] ?></a></p>
    </aside>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
