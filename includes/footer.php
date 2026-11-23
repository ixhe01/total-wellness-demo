<?php $base = base_url(); ?>
</main>

<footer class="footer">
  <div class="wrap footer__grid">
    <div>
      <a class="brand brand--light" href="<?= $base ?>"><span class="emblem emblem--badge" aria-hidden="true"></span>
        <span class="brand__txt"><span class="brand__name">TOTAL</span><span class="brand__sub">Wellness Clinic</span></span></a>
      <p class="footer__tag"><?= t('Look Better · Feel Better · Live Better', 'Luce mejor · Siéntete mejor · Vive mejor') ?></p>
      <div class="social">
        <a href="https://instagram.com/<?= CLINIC['instagram'] ?>" target="_blank" rel="noopener" aria-label="Instagram">IG</a>
        <a href="#" aria-label="Facebook">FB</a><a href="#" aria-label="TikTok">TT</a><a href="#" aria-label="YouTube">YT</a>
      </div>
    </div>
    <div>
      <h4><?= t('Quick Links', 'Enlaces') ?></h4>
      <a href="<?= $base ?>#services"><?= t('Services', 'Servicios') ?></a><a href="<?= $base ?>#about"><?= t('About Us', 'Nosotros') ?></a><a href="<?= $base ?>#locations"><?= t('Locations', 'Ubicaciones') ?></a>
      <a href="<?= app_url('book.php') ?>"><?= t('Book Online Now', 'Agenda en línea') ?></a><a href="<?= app_url('admin/') ?>"><?= t('Staff Portal', 'Portal del personal') ?></a><a href="#"><?= t('Privacy Policy', 'Aviso de privacidad') ?></a>
    </div>
    <div>
      <h4>Van Nuys</h4>
      <p>14540 Victory Blvd, Suite 101<br>Los Angeles, CA 91411</p>
      <h4>Encino</h4>
      <p><?= t('Address coming soon', 'Dirección próximamente') ?></p>
    </div>
    <div>
      <h4><?= t('Contact', 'Contacto') ?></h4>
      <p><a href="tel:<?= CLINIC['phone_raw'] ?>"><?= CLINIC['phone'] ?></a><br><a href="mailto:<?= CLINIC['email'] ?>"><?= CLINIC['email'] ?></a></p>
      <h4><?= t('Hours', 'Horario') ?></h4>
      <p><?= t('Mon–Fri 9:00 AM – 6:00 PM<br>Sat 9:00 AM – 2:00 PM', 'Lun–Vie 9:00 – 18:00<br>Sáb 9:00 – 14:00') ?></p>
    </div>
  </div>
  <div class="wrap footer__legal">
    <p><strong><?= t('Areas we serve:', 'Atendemos:') ?></strong> Van Nuys, Encino, Sherman Oaks, Studio City, North Hollywood, Reseda, Tarzana, Lake Balboa <?= t('and the San Fernando Valley.', 'y todo el Valle de San Fernando.') ?></p>
    <p><?= t('Results vary by individual. Treatments are performed by licensed medical professionals after a consultation. This website is for informational purposes and does not replace medical advice.', 'Los resultados varían en cada persona. Los tratamientos los realizan profesionales médicos con licencia después de una consulta. Este sitio es informativo y no sustituye una consulta médica.') ?></p>
    <p>© <?= date('Y') ?> Total Wellness Clinic | LA · <?= t('Demo site by', 'Sitio demo por') ?> Majestik Studios</p>
  </div>
</footer>

<a class="float-book" href="<?= app_url('book.php') ?>"><?= t('Book Now', 'Agendar cita') ?></a>

<?php if (!empty($show_promo)): ?>
<div class="promo" id="promo" role="dialog" aria-modal="true" aria-labelledby="promoTitle" hidden>
  <div class="promo__bg" data-close></div>
  <div class="promo__box">
    <button class="promo__x" data-close aria-label="<?= t('Close', 'Cerrar') ?>">×</button>
    <div class="promo__img" style="background-image:url('<?= e(img('iv', 900)) ?>')"><span class="promo__tag"><?= t('New patients', 'Pacientes nuevos') ?></span></div>
    <div class="promo__body">
      <p class="eyebrow"><?= t('This month only', 'Solo este mes') ?></p>
      <h2 id="promoTitle"><?= t('$25 off your first IV drip', '$25 de descuento en tu primer suero IV') ?></h2>
      <p><?= t('Plus a <strong>complimentary B12 shot</strong> with your first visit. Recharge, rehydrate and feel the difference in under an hour.', 'Además, una <strong>inyección de B12 de cortesía</strong> en tu primera visita. Recarga energía, hidrátate y siente la diferencia en menos de una hora.') ?></p>
      <a class="btn btn--gold btn--block" href="<?= app_url('book.php?service=9&promo=IV25') ?>"><?= t('Claim my offer', 'Quiero mi promoción') ?></a>
      <button class="promo__later" data-close><?= t('Maybe later', 'Quizá después') ?></button>
      <small class="promo__fine"><?= t('Code IV25 · Sample promotion for the demo · Cannot be combined with other offers.', 'Código IV25 · Promoción de ejemplo para la demo · No acumulable con otras ofertas.') ?></small>
    </div>
  </div>
</div>
<?php endif; ?>

<script src="<?= $base ?>assets/js/main.js?v=4"></script>
<?php if (!empty($extra_js)) foreach ($extra_js as $js) echo '<script src="' . $base . 'assets/js/' . $js . '?v=4"></script>'; ?>
</body>
</html>
