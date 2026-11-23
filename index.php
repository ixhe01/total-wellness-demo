<?php
require __DIR__ . '/includes/db.php';
$is_home = true;
$cats = [
    ['Aesthetics & Injectables', 'injectables', 'Botox, dermal fillers, lip enhancement and facial balancing — natural, refreshed results.', ['Botox / Neurotoxins', 'Dermal Fillers', 'Lip Enhancement', 'Facial Balancing']],
    ['IV Therapy', 'iv', 'Hydration, vitamins and NAD+ delivered directly for energy, immunity and recovery.', ["Myers' Cocktail", 'Hydration Boost', 'Immunity IV', 'NAD+ Infusion']],
    ['Weight Management', 'weight', 'Physician-guided programs with labs, body composition and weekly support.', ['Medical Weight Loss', 'GLP-1 Programs', 'Body Composition', 'Nutrition Coaching']],
    ['Primary Care', 'primary', 'Your family doctor under the same roof: physicals, sick visits, labs and prevention.', ['Annual Physicals', 'Sick Visits', 'Lab Work', 'Chronic Care']],
    ['Skin & Laser', 'facial', 'Medical facials, microneedling, peels and laser treatments for healthy, glowing skin.', ['Medical Facials', 'Microneedling', 'Chemical Peels', 'Laser Hair Removal']],
    ['Wellness', 'wellness', 'Vitamin shots, hormone balance and whole-body wellness plans built around you.', ['B12 Shots', 'Hormone Balance', 'Wellness Plans', 'Memberships']],
];
$features = [
    ['IV Therapy', 'iv', 'Feel the difference in under an hour', "Our IV drips deliver fluids, vitamins and antioxidants straight to your bloodstream, so your body absorbs 100% of what it needs. Relax in a private lounge while our nurses take care of everything — perfect for energy, recovery, immunity or that post-flight reset."],
    ['Botox & Dermal Fillers', 'injectables', 'Refreshed, never overdone', 'Our injectors combine medical precision with an artist’s eye to soften lines, restore volume and enhance your natural features. Every plan starts with a face-mapping consultation so your results look like you — just more rested.'],
    ['Medical Weight Management', 'weight', 'A plan that finally fits your life', 'Lasting results come from medicine plus support. We review your labs and goals, build a physician-guided program (including GLP-1 options when appropriate) and check in every week so you never do it alone.'],
    ['Primary Care', 'primary', 'Your health, all in one place', 'From annual physicals to same-week sick visits and in-office labs, our providers get to know you and your family. Because true wellness starts with knowing your numbers.'],
    ['Medical Facials & Skin', 'facial', 'Healthy skin is the best foundation', 'Customized medical-grade facials, microneedling and peels that treat texture, pigmentation and acne — and keep your glow going between treatments.'],
];
$team = [['team1', 'Aesthetic Injector', 'RN · Injectables & Skin'], ['team2', 'Medical Director', 'MD / NP · Primary Care & Weight Loss'],
         ['team3', 'Skin Specialist', 'Licensed Esthetician · Laser'], ['team4', 'IV Nurse', 'LVN · IV Therapy & Wellness']];
include __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section class="hero">
  <div class="hero__txt">
    <div class="hero__in reveal">
      <p class="eyebrow">Medical · Wellness · Aesthetics</p>
      <h1>Look better.<br><em>Feel better.</em><br>Live better.</h1>
      <p class="hero__lead">Aesthetics, IV therapy, weight management and primary care — together under one roof in the San Fernando Valley.</p>
      <div class="hero__ctas">
        <a class="btn btn--gold" href="<?= app_url('book.php') ?>">Request an Appointment</a>
        <a class="btn btn--line" href="<?= app_url('book.php?service=1') ?>">Free Consultation</a>
      </div>
    </div>
  </div>
  <div class="hero__media"><img src="<?= e(img('group', 2000)) ?>" alt="The Total Wellness Clinic medical team"></div>
</section>

<!-- 3 FEATURE BLOCKS -->
<section class="pillars">
  <div class="wrap pillars__grid">
    <article class="pillar reveal"><span class="pillar__n">01</span><h3>Look Better</h3><p>Injectables, facials and laser treatments that enhance your natural beauty.</p><a href="<?= app_url('book.php') ?>" class="link">Book aesthetics →</a></article>
    <article class="pillar pillar--sage reveal"><span class="pillar__n">02</span><h3>Feel Better</h3><p>IV therapy, vitamin shots and wellness plans to recharge body and mind.</p><a href="<?= app_url('book.php') ?>" class="link">Book IV therapy →</a></article>
    <article class="pillar pillar--ink reveal"><span class="pillar__n">03</span><h3>Live Better</h3><p>Primary care and medical weight management for long-term health.</p><a href="<?= app_url('book.php') ?>" class="link">Book a visit →</a></article>
  </div>
</section>

<!-- WELCOME -->
<section class="welcome" id="about">
  <div class="wrap welcome__grid">
    <div class="welcome__img reveal"><img src="<?= e(img('duo', 1200)) ?>" alt="Total Wellness Clinic providers" loading="lazy"></div>
    <div class="reveal">
      <p class="eyebrow">Welcome to Total Wellness Clinic</p>
      <h2>Where modern medicine meets <em>whole-body</em> self-care</h2>
      <p>At Total Wellness Clinic we believe looking good and feeling good go hand in hand. Our licensed medical team brings together aesthetics, IV therapy, weight management and primary care, so you can take care of every part of your health with people who already know you.</p>
      <p>Every visit begins with listening. Then we design a plan around your goals, your schedule and your budget — with natural-looking results and honest guidance every step of the way.</p>
      <ul class="checks">
        <li>Licensed physicians, nurses and estheticians</li>
        <li>Two Valley locations: Van Nuys &amp; Encino</li>
        <li>Se habla español</li>
      </ul>
      <a class="btn btn--dark" href="#team">Meet the Team</a>
    </div>
  </div>
</section>

<!-- SERVICE CATEGORIES -->
<section class="services" id="services">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow">What can we do for you?</p><h2>Our Services</h2></div>
    <div class="cards">
      <?php foreach ($cats as [$t, $im, $d, $list]): ?>
      <article class="card reveal">
        <div class="card__img"><img src="<?= e(img($im, 800)) ?>" alt="<?= e($t) ?>" loading="lazy"></div>
        <div class="card__body">
          <h3><?= e($t) ?></h3>
          <p><?= e($d) ?></p>
          <ul><?php foreach ($list as $l) echo '<li>' . e($l) . '</li>'; ?></ul>
          <a class="link" href="<?= app_url('book.php') ?>">Book now →</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- STRIP -->
<section class="strip">
  <div class="wrap strip__grid">
    <a class="strip__item" href="<?= app_url('book.php') ?>"><span>Book Your Appointment</span><small>Online, 24/7 →</small></a>
    <a class="strip__item" href="#memberships"><span>Monthly Specials</span><small>Memberships &amp; packages →</small></a>
    <a class="strip__item" href="#locations"><span>Find a Location</span><small>Van Nuys · Encino →</small></a>
  </div>
</section>

<!-- STATEMENT -->
<section class="statement">
  <div class="statement__bg" style="background-image:url('<?= e(img('lobby', 1800)) ?>')"></div>
  <div class="wrap statement__in reveal">
    <span class="emblem emblem--xl" aria-hidden="true"></span>
    <h2>When medical expertise meets <em>genuine care</em></h2>
    <p>We created Total Wellness Clinic so families in the Valley could get premium aesthetic and wellness care with the trust of a medical practice — without driving across town or paying Beverly Hills prices.</p>
    <a class="btn btn--gold" href="<?= app_url('book.php') ?>">Start with a free consultation</a>
  </div>
</section>

<!-- FEATURED TREATMENTS -->
<section class="features">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow">Featured Treatments</p><h2>Most-loved at Total Wellness</h2></div>
    <?php foreach ($features as $i => [$t, $im, $sub, $txt]): ?>
    <article class="feature <?= $i % 2 ? 'feature--rev' : '' ?> reveal">
      <div class="feature__img"><img src="<?= e(img($im, 1100)) ?>" alt="<?= e($t) ?>" loading="lazy"></div>
      <div class="feature__txt">
        <p class="eyebrow"><?= e($sub) ?></p>
        <h3><?= e($t) ?></h3>
        <p><?= e($txt) ?></p>
        <div class="feature__btns"><a class="btn btn--dark" href="<?= app_url('book.php') ?>">Book Now</a><a class="btn btn--line" href="#contact">Ask a Question</a></div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- TEAM -->
<section class="team" id="team">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow">Meet the Team</p><h2>The people behind your care</h2></div>
    <div class="team__grid">
      <?php foreach ($team as [$im, $n, $r]): ?>
      <figure class="member reveal">
        <div class="member__img"><img src="<?= e(img($im, 700)) ?>" alt="<?= e($n) ?>" loading="lazy"></div>
        <figcaption><strong><?= e($n) ?></strong><span><?= e($r) ?></span></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- MEMBERSHIPS -->
<section class="members" id="memberships">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow">Memberships</p><h2>Wellness that fits your month</h2><p class="muted">Sample tiers for the demo — final pricing to be confirmed by the clinic.</p></div>
    <div class="tiers">
      <div class="tier reveal"><h3>Glow</h3><p class="tier__price">$99<small>/mo</small></p><ul><li>1 medical facial or peel</li><li>10% off injectables</li><li>Member-only specials</li></ul><a class="btn btn--line" href="#contact">Join</a></div>
      <div class="tier tier--hi reveal"><span class="tier__badge">Most popular</span><h3>Revive</h3><p class="tier__price">$179<small>/mo</small></p><ul><li>1 IV drip of your choice</li><li>2 B12 shots</li><li>15% off all services</li></ul><a class="btn btn--gold" href="#contact">Join</a></div>
      <div class="tier reveal"><h3>Total</h3><p class="tier__price">$299<small>/mo</small></p><ul><li>IV drip + medical facial</li><li>Priority booking</li><li>20% off injectables</li></ul><a class="btn btn--line" href="#contact">Join</a></div>
    </div>
  </div>
</section>

<!-- INSTAGRAM -->
<section class="insta">
  <div class="wrap insta__in reveal">
    <p class="eyebrow">Follow us</p>
    <h2><a href="https://instagram.com/<?= CLINIC['instagram'] ?>" target="_blank" rel="noopener">@<?= CLINIC['instagram'] ?></a></h2>
    <div class="insta__grid">
      <?php foreach (['team1', 'iv', 'team2', 'facial', 'team3', 'injectables'] as $k): ?>
        <a href="https://instagram.com/<?= CLINIC['instagram'] ?>" target="_blank" rel="noopener"><img src="<?= e(img($k, 500)) ?>" alt="" loading="lazy"></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="reviews">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow">Testimonials</p><h2>“I finally feel like myself again”</h2><p class="muted">Placeholder reviews for the demo — replace with real Google reviews.</p></div>
    <div class="reviews__grid">
      <blockquote class="review reveal"><p>“The IV lounge is so relaxing and the nurses are amazing. I come every month now.”</p><cite>Patient name · IV Therapy</cite></blockquote>
      <blockquote class="review reveal"><p>“My Botox looks completely natural. They took the time to explain everything.”</p><cite>Patient name · Injectables</cite></blockquote>
      <blockquote class="review reveal"><p>“Down 30 lbs with the weight program. The weekly check-ins kept me on track.”</p><cite>Patient name · Weight Management</cite></blockquote>
      <blockquote class="review reveal"><p>“Primary care and aesthetics in one place — my whole family comes here.”</p><cite>Patient name · Primary Care</cite></blockquote>
    </div>
  </div>
</section>

<!-- LOCATIONS -->
<section class="locations" id="locations">
  <div class="wrap locations__grid">
    <div class="loc reveal">
      <p class="eyebrow">Location 01</p><h3>Van Nuys</h3>
      <p>14540 Victory Blvd, Suite 101<br>Los Angeles, CA 91411</p>
      <p class="muted">Mon–Fri 9–6 · Sat 9–2</p>
      <div class="feature__btns"><a class="btn btn--dark" href="<?= app_url('book.php?location=1') ?>">Book in Van Nuys</a><a class="btn btn--line" target="_blank" rel="noopener" href="https://maps.google.com/?q=14540+Victory+Blvd+Suite+101+Los+Angeles+CA+91411">Directions</a></div>
    </div>
    <div class="loc reveal">
      <p class="eyebrow">Location 02</p><h3>Encino</h3>
      <p>Address coming soon<br>Encino, CA</p>
      <p class="muted">Mon–Fri 9–6 · Sat 9–2</p>
      <div class="feature__btns"><a class="btn btn--dark" href="<?= app_url('book.php?location=2') ?>">Book in Encino</a></div>
    </div>
    <iframe class="map reveal" title="Map" loading="lazy" src="https://maps.google.com/maps?q=14540%20Victory%20Blvd%20Suite%20101%20Los%20Angeles%20CA%2091411&z=14&output=embed"></iframe>
  </div>
</section>

<!-- CONTACT / CONSULTATION -->
<section class="contact" id="contact">
  <div class="wrap contact__grid">
    <div class="reveal">
      <p class="eyebrow">Free Consultation</p>
      <h2>Have a question?<br><em>We’re here to help.</em></h2>
      <p>Tell us a little about your goals and a member of our team will reach out within one business day. Prefer to talk? Call <a href="tel:<?= CLINIC['phone_raw'] ?>"><?= CLINIC['phone'] ?></a>.</p>
    </div>
    <form class="form reveal" id="leadForm" novalidate>
      <div class="form__row"><label>Name<input name="name" required></label><label>Phone<input name="phone" type="tel"></label></div>
      <label>Email<input name="email" type="email"></label>
      <div class="form__row">
        <label>I’m interested in<select name="reason"><option>Aesthetics &amp; Injectables</option><option>IV Therapy</option><option>Weight Management</option><option>Primary Care</option><option>Skin &amp; Laser</option><option>Memberships</option><option>Other</option></select></label>
        <label>Preferred location<select name="location"><option>Van Nuys</option><option>Encino</option></select></label>
      </div>
      <label>Message<textarea name="message" rows="3"></textarea></label>
      <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">
      <button class="btn btn--gold" type="submit">Send Request</button>
      <p class="form__msg" role="status"></p>
    </form>
  </div>
</section>

<!-- CLOSING CTA -->
<section class="closing">
  <div class="wrap closing__in reveal">
    <h2>Ready to book?</h2>
    <p>Choose your treatment, provider and time in less than a minute.</p>
    <a class="btn btn--gold" href="<?= app_url('book.php') ?>">Book Online Now</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
