<?php
require __DIR__ . '/includes/db.php';
$is_home = true;
$show_promo = true;
$cats = [
    [t('Aesthetics & Injectables', 'Estética e Inyectables'), 'injectables', t('Botox, dermal fillers, lip enhancement and facial balancing — natural, refreshed results.', 'Bótox, rellenos, aumento de labios y armonización facial con resultados naturales.'),
        [t('Botox / Neurotoxins', 'Bótox'), t('Dermal Fillers', 'Rellenos dérmicos'), t('Lip Enhancement', 'Labios'), t('Facial Balancing', 'Armonización facial')], 2],
    [t('IV Therapy', 'Terapia IV'), 'iv', t('Hydration, vitamins and NAD+ delivered directly for energy, immunity and recovery.', 'Hidratación, vitaminas y NAD+ directo a tu cuerpo para energía, defensas y recuperación.'),
        ["Myers' Cocktail", t('Hydration Boost', 'Hidratación'), t('Immunity IV', 'Inmunidad'), 'NAD+'], 9],
    [t('Weight Management', 'Control de Peso'), 'weight', t('Physician-guided programs with labs, body composition and weekly support.', 'Programas guiados por médicos con laboratorios, composición corporal y apoyo semanal.'),
        [t('Medical Weight Loss', 'Pérdida de peso médica'), t('GLP-1 Programs', 'Programas GLP-1'), t('Body Composition', 'Composición corporal'), t('Nutrition Coaching', 'Asesoría nutricional')], 14],
    [t('Primary Care', 'Medicina General'), 'primary', t('Your family doctor under the same roof: physicals, sick visits, labs and prevention.', 'Tu médico familiar en el mismo lugar: chequeos, consultas, laboratorios y prevención.'),
        [t('Annual Physicals', 'Chequeos anuales'), t('Sick Visits', 'Consultas'), t('Lab Work', 'Laboratorio'), t('Chronic Care', 'Enfermedades crónicas')], 16],
    [t('Skin & Laser', 'Piel y Láser'), 'facial', t('Medical facials, microneedling, peels and laser treatments for healthy, glowing skin.', 'Faciales médicos, microneedling, peelings y láser para una piel sana y luminosa.'),
        [t('Medical Facials', 'Faciales médicos'), 'Microneedling', t('Chemical Peels', 'Peelings'), t('Laser Hair Removal', 'Depilación láser')], 5],
    [t('Wellness', 'Bienestar'), 'wellness', t('Vitamin shots, hormone balance and whole-body wellness plans built around you.', 'Vitaminas inyectables, balance hormonal y planes de bienestar hechos para ti.'),
        [t('B12 Shots', 'Inyecciones B12'), t('Hormone Balance', 'Balance hormonal'), t('Wellness Plans', 'Planes de bienestar'), t('Memberships', 'Membresías')], 1],
];
$features = [
    [t('IV Therapy', 'Terapia IV'), 'iv', t('Feel the difference in under an hour', 'Siente la diferencia en menos de una hora'), 9,
     t('Our IV drips deliver fluids, vitamins and antioxidants straight to your bloodstream, so your body absorbs 100% of what it needs. Relax in a private lounge while our nurses take care of everything — perfect for energy, recovery, immunity or that post-flight reset.',
       'Nuestros sueros IV llevan líquidos, vitaminas y antioxidantes directo al torrente sanguíneo para que tu cuerpo absorba el 100%. Relájate en un lounge privado mientras nuestras enfermeras se encargan de todo: ideal para energía, recuperación, defensas o después de un viaje.')],
    [t('Botox & Dermal Fillers', 'Bótox y Rellenos'), 'injectables', t('Refreshed, never overdone', 'Fresca, nunca exagerada'), 2,
     t('Our injectors combine medical precision with an artist’s eye to soften lines, restore volume and enhance your natural features. Every plan starts with a face-mapping consultation so your results look like you — just more rested.',
       'Nuestras inyectoras combinan precisión médica con ojo artístico para suavizar líneas, devolver volumen y resaltar tus rasgos. Cada plan inicia con un análisis facial para que el resultado se vea como tú, solo más descansada.')],
    [t('Medical Weight Management', 'Control de Peso Médico'), 'weight', t('A plan that finally fits your life', 'Un plan que por fin se adapta a tu vida'), 14,
     t('Lasting results come from medicine plus support. We review your labs and goals, build a physician-guided program (including GLP-1 options when appropriate) and check in every week so you never do it alone.',
       'Los resultados duraderos vienen de medicina más acompañamiento. Revisamos tus laboratorios y metas, armamos un programa guiado por médico (incluyendo GLP-1 cuando es apropiado) y te damos seguimiento cada semana.')],
    [t('Primary Care', 'Medicina General'), 'primary', t('Your health, all in one place', 'Tu salud, en un solo lugar'), 16,
     t('From annual physicals to same-week sick visits and in-office labs, our providers get to know you and your family. Because true wellness starts with knowing your numbers.',
       'Desde chequeos anuales hasta consultas en la misma semana y laboratorio en clínica, nuestro equipo conoce a ti y a tu familia. El bienestar real empieza por conocer tus números.')],
    [t('Medical Facials & Skin', 'Faciales Médicos y Piel'), 'facial', t('Healthy skin is the best foundation', 'Una piel sana es la mejor base'), 5,
     t('Customized medical-grade facials, microneedling and peels that treat texture, pigmentation and acne — and keep your glow going between treatments.',
       'Faciales de grado médico, microneedling y peelings a tu medida para textura, manchas y acné, y para mantener tu brillo entre tratamientos.')],
];
$team = [['team1', 'Aesthetic Injector', 'RN · Injectables & Skin'], ['team2', 'Medical Director', 'MD / NP · Primary Care & Weight Loss'],
         ['team3', 'Skin Specialist', 'Licensed Esthetician · Laser'], ['team4', 'IV Nurse', 'LVN · IV Therapy & Wellness']];
include __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section class="hero">
  <div class="hero__txt">
    <div class="hero__in reveal">
      <p class="eyebrow"><?= t('Medical · Wellness · Aesthetics', 'Medicina · Bienestar · Estética') ?></p>
      <h1><?= t('Look better.<br><em>Feel better.</em><br>Live better.', 'Luce mejor.<br><em>Siéntete mejor.</em><br>Vive mejor.') ?></h1>
      <p class="hero__lead"><?= t('Aesthetics, IV therapy, weight management and primary care — together under one roof in the San Fernando Valley.', 'Estética, terapia IV, control de peso y medicina general en un solo lugar, en el Valle de San Fernando.') ?></p>
      <div class="hero__ctas">
        <a class="btn btn--gold" href="<?= app_url('book.php') ?>"><?= t('Book an Appointment', 'Agendar una cita') ?></a>
        <a class="btn btn--line" href="<?= app_url('book.php?service=1') ?>"><?= t('Free Consultation', 'Consulta gratuita') ?></a>
      </div>
      <p class="hero__note"><span>★★★★★</span> <?= t('Se habla español · Licensed medical team', 'Hablamos español · Equipo médico certificado') ?></p>
    </div>
  </div>
  <div class="hero__media"><img src="<?= e(img('group', 2000)) ?>" alt="<?= t('The Total Wellness Clinic medical team', 'El equipo médico de Total Wellness Clinic') ?>" fetchpriority="high"></div>
</section>

<!-- 3 FEATURE BLOCKS -->
<section class="pillars">
  <div class="wrap pillars__grid">
    <article class="pillar reveal"><span class="pillar__n">01</span><h3><?= t('Look Better', 'Luce mejor') ?></h3><p><?= t('Injectables, facials and laser treatments that enhance your natural beauty.', 'Inyectables, faciales y láser que resaltan tu belleza natural.') ?></p><a href="<?= app_url('book.php?service=2') ?>" class="link"><?= t('Book aesthetics', 'Agendar estética') ?> →</a></article>
    <article class="pillar pillar--sage reveal"><span class="pillar__n">02</span><h3><?= t('Feel Better', 'Siéntete mejor') ?></h3><p><?= t('IV therapy, vitamin shots and wellness plans to recharge body and mind.', 'Terapia IV, vitaminas y planes de bienestar para recargar cuerpo y mente.') ?></p><a href="<?= app_url('book.php?service=9') ?>" class="link"><?= t('Book IV therapy', 'Agendar terapia IV') ?> →</a></article>
    <article class="pillar pillar--ink reveal"><span class="pillar__n">03</span><h3><?= t('Live Better', 'Vive mejor') ?></h3><p><?= t('Primary care and medical weight management for long-term health.', 'Medicina general y control de peso para tu salud a largo plazo.') ?></p><a href="<?= app_url('book.php?service=16') ?>" class="link"><?= t('Book a visit', 'Agendar consulta') ?> →</a></article>
  </div>
</section>

<!-- WELCOME -->
<section class="welcome" id="about">
  <div class="wrap welcome__grid">
    <div class="welcome__img reveal"><img src="<?= e(img('duo', 1200)) ?>" alt="<?= t('Total Wellness Clinic providers', 'Proveedores de Total Wellness Clinic') ?>" loading="lazy"></div>
    <div class="reveal">
      <p class="eyebrow"><?= t('Welcome to Total Wellness Clinic', 'Bienvenido a Total Wellness Clinic') ?></p>
      <h2><?= t('Where modern medicine meets <em>whole-body</em> self-care', 'Donde la medicina moderna se une al <em>cuidado integral</em>') ?></h2>
      <p><?= t('At Total Wellness Clinic we believe looking good and feeling good go hand in hand. Our licensed medical team brings together aesthetics, IV therapy, weight management and primary care, so you can take care of every part of your health with people who already know you.', 'En Total Wellness Clinic creemos que verse bien y sentirse bien van de la mano. Nuestro equipo médico certificado reúne estética, terapia IV, control de peso y medicina general para que cuides cada parte de tu salud con personas que ya te conocen.') ?></p>
      <p><?= t('Every visit begins with listening. Then we design a plan around your goals, your schedule and your budget — with natural-looking results and honest guidance every step of the way.', 'Cada visita empieza escuchándote. Después diseñamos un plan según tus metas, tu horario y tu presupuesto, con resultados naturales y una guía honesta en cada paso.') ?></p>
      <ul class="checks">
        <li><?= t('Licensed physicians, nurses and estheticians', 'Médicos, enfermeras y cosmetólogas certificados') ?></li>
        <li><?= t('Two Valley locations: Van Nuys &amp; Encino', 'Dos sucursales: Van Nuys y Encino') ?></li>
        <li><?= t('Se habla español', 'Atención en español e inglés') ?></li>
      </ul>
      <a class="btn btn--dark" href="#team"><?= t('Meet the Team', 'Conoce al equipo') ?></a>
    </div>
  </div>
</section>

<!-- SERVICE CATEGORIES -->
<section class="services" id="services">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow"><?= t('What can we do for you?', '¿En qué podemos ayudarte?') ?></p><h2><?= t('Our Services', 'Nuestros servicios') ?></h2></div>
    <div class="cards">
      <?php foreach ($cats as [$tt, $im, $d, $list, $sid]): ?>
      <article class="card reveal">
        <a class="card__img" href="<?= app_url("book.php?service=$sid") ?>" tabindex="-1" aria-hidden="true"><img src="<?= e(img($im, 800)) ?>" alt="" loading="lazy"></a>
        <div class="card__body">
          <h3><?= $tt ?></h3>
          <p><?= $d ?></p>
          <ul><?php foreach ($list as $l) echo '<li>' . $l . '</li>'; ?></ul>
          <a class="link" href="<?= app_url("book.php?service=$sid") ?>"><?= t('Book now', 'Agendar') ?> →</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- STRIP -->
<section class="strip">
  <div class="wrap strip__grid">
    <a class="strip__item" href="<?= app_url('book.php') ?>"><span><?= t('Book Your Appointment', 'Agenda tu cita') ?></span><small><?= t('Online, 24/7', 'En línea, 24/7') ?> →</small></a>
    <a class="strip__item" href="#memberships"><span><?= t('Monthly Specials', 'Promociones del mes') ?></span><small><?= t('Memberships &amp; packages', 'Membresías y paquetes') ?> →</small></a>
    <a class="strip__item" href="#locations"><span><?= t('Find a Location', 'Sucursales') ?></span><small>Van Nuys · Encino →</small></a>
  </div>
</section>

<!-- STATEMENT -->
<section class="statement">
  <div class="statement__bg" style="background-image:url('<?= e(img('group', 1800)) ?>')"></div>
  <div class="wrap statement__in reveal">
    <span class="emblem emblem--xl" aria-hidden="true"></span>
    <h2><?= t('When medical expertise meets <em>genuine care</em>', 'Cuando la experiencia médica se une al <em>cuidado genuino</em>') ?></h2>
    <p><?= t('We created Total Wellness Clinic so families in the Valley could get premium aesthetic and wellness care with the trust of a medical practice — without driving across town or paying Beverly Hills prices.', 'Creamos Total Wellness Clinic para que las familias del Valle tengan estética y bienestar de primer nivel con la confianza de una clínica médica, sin cruzar la ciudad ni pagar precios de Beverly Hills.') ?></p>
    <a class="btn btn--gold" href="<?= app_url('book.php?service=1') ?>"><?= t('Start with a free consultation', 'Empieza con una consulta gratis') ?></a>
  </div>
</section>

<!-- FEATURED TREATMENTS -->
<section class="features">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow"><?= t('Featured Treatments', 'Tratamientos destacados') ?></p><h2><?= t('Most-loved at Total Wellness', 'Los favoritos de nuestros pacientes') ?></h2></div>
    <?php foreach ($features as $i => [$tt, $im, $sub, $sid, $txt]): ?>
    <article class="feature <?= $i % 2 ? 'feature--rev' : '' ?> reveal">
      <div class="feature__img"><img src="<?= e(img($im, 1100)) ?>" alt="<?= e(strip_tags($tt)) ?>" loading="lazy"></div>
      <div class="feature__txt">
        <p class="eyebrow"><?= $sub ?></p>
        <h3><?= $tt ?></h3>
        <p><?= $txt ?></p>
        <div class="feature__btns"><a class="btn btn--dark" href="<?= app_url("book.php?service=$sid") ?>"><?= t('Book Now', 'Agendar') ?></a><a class="btn btn--line" href="#contact"><?= t('Ask a Question', 'Hacer una pregunta') ?></a></div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- TEAM -->
<section class="team" id="team">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow"><?= t('Meet the Team', 'Nuestro equipo') ?></p><h2><?= t('The people behind your care', 'Las personas que te cuidan') ?></h2></div>
    <div class="team__grid">
      <?php foreach ($team as [$im, $n, $r]): ?>
      <figure class="member reveal">
        <div class="member__img"><img src="<?= e(img($im, 700)) ?>" alt="<?= e(tr($n)) ?>" loading="lazy"></div>
        <figcaption><strong><?= e(tr($n)) ?></strong><span><?= e(tr($r)) ?></span></figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- MEMBERSHIPS -->
<section class="members" id="memberships">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow"><?= t('Memberships', 'Membresías') ?></p><h2><?= t('Wellness that fits your month', 'Bienestar que cabe en tu mes') ?></h2><p class="muted"><?= t('Sample tiers for the demo — final pricing to be confirmed by the clinic.', 'Planes de ejemplo para la demo; precios finales por confirmar con la clínica.') ?></p></div>
    <div class="tiers">
      <div class="tier reveal"><h3>Glow</h3><p class="tier__price">$99<small>/<?= t('mo', 'mes') ?></small></p><ul><li><?= t('1 medical facial or peel', '1 facial médico o peeling') ?></li><li><?= t('10% off injectables', '10% en inyectables') ?></li><li><?= t('Member-only specials', 'Promociones exclusivas') ?></li></ul><a class="btn btn--line" href="#contact"><?= t('Join', 'Unirme') ?></a></div>
      <div class="tier tier--hi reveal"><span class="tier__badge"><?= t('Most popular', 'Más popular') ?></span><h3>Revive</h3><p class="tier__price">$179<small>/<?= t('mo', 'mes') ?></small></p><ul><li><?= t('1 IV drip of your choice', '1 suero IV a elegir') ?></li><li><?= t('2 B12 shots', '2 inyecciones de B12') ?></li><li><?= t('15% off all services', '15% en todos los servicios') ?></li></ul><a class="btn btn--gold" href="#contact"><?= t('Join', 'Unirme') ?></a></div>
      <div class="tier reveal"><h3>Total</h3><p class="tier__price">$299<small>/<?= t('mo', 'mes') ?></small></p><ul><li><?= t('IV drip + medical facial', 'Suero IV + facial médico') ?></li><li><?= t('Priority booking', 'Agenda prioritaria') ?></li><li><?= t('20% off injectables', '20% en inyectables') ?></li></ul><a class="btn btn--line" href="#contact"><?= t('Join', 'Unirme') ?></a></div>
    </div>
  </div>
</section>

<!-- INSTAGRAM -->
<section class="insta">
  <div class="wrap insta__in reveal">
    <p class="eyebrow"><?= t('Follow us', 'Síguenos') ?></p>
    <h2><a href="https://instagram.com/<?= CLINIC['instagram'] ?>" target="_blank" rel="noopener">@<?= CLINIC['instagram'] ?></a></h2>
    <div class="insta__grid">
      <?php foreach (['team1', 'iv', 'team2', 'facial', 'team3', 'injectables'] as $k): ?>
        <a href="https://instagram.com/<?= CLINIC['instagram'] ?>" target="_blank" rel="noopener" aria-label="Instagram"><img src="<?= e(img($k, 500)) ?>" alt="" loading="lazy"></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="reviews">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow"><?= t('Testimonials', 'Testimonios') ?></p><h2><?= t('“I finally feel like myself again”', '“Por fin me siento yo otra vez”') ?></h2><p class="muted"><?= t('Placeholder reviews for the demo — replace with real Google reviews.', 'Reseñas de ejemplo para la demo; reemplazar con reseñas reales de Google.') ?></p></div>
    <div class="reviews__grid">
      <blockquote class="review reveal"><p><?= t('“The IV lounge is so relaxing and the nurses are amazing. I come every month now.”', '“El lounge de sueros es súper relajante y las enfermeras son increíbles. Ya vengo cada mes.”') ?></p><cite><?= t('Patient name · IV Therapy', 'Nombre del paciente · Terapia IV') ?></cite></blockquote>
      <blockquote class="review reveal"><p><?= t('“My Botox looks completely natural. They took the time to explain everything.”', '“Mi bótox se ve completamente natural. Me explicaron todo con calma.”') ?></p><cite><?= t('Patient name · Injectables', 'Nombre del paciente · Inyectables') ?></cite></blockquote>
      <blockquote class="review reveal"><p><?= t('“Down 30 lbs with the weight program. The weekly check-ins kept me on track.”', '“Bajé 30 libras con el programa. El seguimiento semanal me mantuvo enfocada.”') ?></p><cite><?= t('Patient name · Weight Management', 'Nombre del paciente · Control de peso') ?></cite></blockquote>
      <blockquote class="review reveal"><p><?= t('“Primary care and aesthetics in one place — my whole family comes here.”', '“Medicina general y estética en un solo lugar. Aquí viene toda mi familia.”') ?></p><cite><?= t('Patient name · Primary Care', 'Nombre del paciente · Medicina general') ?></cite></blockquote>
    </div>
  </div>
</section>

<!-- LOCATIONS -->
<section class="locations" id="locations">
  <div class="wrap">
    <div class="sec-head reveal"><p class="eyebrow"><?= t('Locations', 'Ubicaciones') ?></p><h2><?= t('Two locations in the Valley', 'Dos sucursales en el Valle') ?></h2></div>
    <div class="locations__grid">
      <div class="loc reveal">
        <p class="eyebrow">01</p><h3>Van Nuys</h3>
        <p>14540 Victory Blvd, Suite 101<br>Los Angeles, CA 91411</p>
        <p class="muted"><?= t('Mon–Fri 9–6 · Sat 9–2', 'Lun–Vie 9–18 · Sáb 9–14') ?></p>
        <div class="feature__btns"><a class="btn btn--dark" href="<?= app_url('book.php?location=1') ?>"><?= t('Book here', 'Agendar aquí') ?></a><a class="btn btn--line" target="_blank" rel="noopener" href="https://maps.google.com/?q=14540+Victory+Blvd+Suite+101+Los+Angeles+CA+91411"><?= t('Directions', 'Cómo llegar') ?></a></div>
      </div>
      <div class="loc reveal">
        <p class="eyebrow">02</p><h3>Encino</h3>
        <p><?= t('Address coming soon', 'Dirección próximamente') ?><br>Encino, CA</p>
        <p class="muted"><?= t('Mon–Fri 9–6 · Sat 9–2', 'Lun–Vie 9–18 · Sáb 9–14') ?></p>
        <div class="feature__btns"><a class="btn btn--dark" href="<?= app_url('book.php?location=2') ?>"><?= t('Book here', 'Agendar aquí') ?></a></div>
      </div>
      <iframe class="map reveal" title="Map" loading="lazy" src="https://maps.google.com/maps?q=14540%20Victory%20Blvd%20Suite%20101%20Los%20Angeles%20CA%2091411&z=14&hl=<?= lang() ?>&output=embed"></iframe>
    </div>
  </div>
</section>

<!-- CONTACT / CONSULTATION -->
<section class="contact" id="contact">
  <div class="wrap contact__grid">
    <div class="reveal">
      <p class="eyebrow"><?= t('Free Consultation', 'Consulta gratuita') ?></p>
      <h2><?= t('Have a question?<br><em>We’re here to help.</em>', '¿Tienes una pregunta?<br><em>Estamos para ayudarte.</em>') ?></h2>
      <p><?= t('Tell us a little about your goals and a member of our team will reach out within one business day. Prefer to talk? Call', 'Cuéntanos un poco sobre tus metas y alguien del equipo te contactará en un día hábil. ¿Prefieres hablar? Llama al') ?> <a href="tel:<?= CLINIC['phone_raw'] ?>"><?= CLINIC['phone'] ?></a>.</p>
    </div>
    <form class="form reveal" id="leadForm" novalidate>
      <div class="form__row"><label><?= t('Name', 'Nombre') ?><input name="name" required autocomplete="name"></label><label><?= t('Phone', 'Teléfono') ?><input name="phone" type="tel" autocomplete="tel" inputmode="tel"></label></div>
      <label><?= t('Email', 'Correo') ?><input name="email" type="email" autocomplete="email" inputmode="email"></label>
      <div class="form__row">
        <label><?= t('I’m interested in', 'Me interesa') ?><select name="reason">
          <?php foreach ([['Aesthetics & Injectables', 'Estética e inyectables'], ['IV Therapy', 'Terapia IV'], ['Weight Management', 'Control de peso'], ['Primary Care', 'Medicina general'], ['Skin & Laser', 'Piel y láser'], ['Memberships', 'Membresías'], ['Other', 'Otro']] as [$en, $es]) echo '<option value="' . e($en) . '">' . e(t($en, $es)) . '</option>'; ?>
        </select></label>
        <label><?= t('Preferred location', 'Sucursal') ?><select name="location"><option>Van Nuys</option><option>Encino</option></select></label>
      </div>
      <label><?= t('Message', 'Mensaje') ?><textarea name="message" rows="3"></textarea></label>
      <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      <button class="btn btn--gold" type="submit"><?= t('Send Request', 'Enviar') ?></button>
      <p class="form__msg" role="status"></p>
    </form>
  </div>
</section>

<!-- CLOSING CTA -->
<section class="closing">
  <div class="wrap closing__in reveal">
    <h2><?= t('Ready to book?', '¿Quieres agendar tu cita?') ?></h2>
    <p><?= t('Choose your treatment, provider and time in less than a minute.', 'Elige tratamiento, especialista y horario en menos de un minuto.') ?></p>
    <a class="btn btn--gold" href="<?= app_url('book.php') ?>"><?= t('Book Online Now', 'Agendar en línea') ?></a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
