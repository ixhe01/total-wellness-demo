<?php $base = base_url(); ?>
</main>

<footer class="footer">
  <div class="wrap footer__grid">
    <div>
      <a class="brand brand--light" href="<?= $base ?>"><span class="emblem emblem--badge" aria-hidden="true"></span>
        <span class="brand__txt"><span class="brand__name">TOTAL</span><span class="brand__sub">Wellness Clinic</span></span></a>
      <p class="footer__tag"><?= CLINIC['tagline'] ?></p>
      <div class="social">
        <a href="https://instagram.com/<?= CLINIC['instagram'] ?>" target="_blank" rel="noopener" aria-label="Instagram">IG</a>
        <a href="#" aria-label="Facebook">FB</a><a href="#" aria-label="TikTok">TT</a><a href="#" aria-label="YouTube">YT</a>
      </div>
    </div>
    <div>
      <h4>Quick Links</h4>
      <a href="<?= $base ?>#services">Services</a><a href="<?= $base ?>#about">About Us</a><a href="<?= $base ?>#locations">Locations</a>
      <a href="<?= app_url('book.php') ?>">Book Online Now</a><a href="<?= app_url('admin/') ?>">Staff Portal</a><a href="#">Privacy Policy</a>
    </div>
    <div>
      <h4>Van Nuys</h4>
      <p>14540 Victory Blvd, Suite 101<br>Los Angeles, CA 91411</p>
      <h4>Encino</h4>
      <p>Address coming soon</p>
    </div>
    <div>
      <h4>Contact</h4>
      <p><a href="tel:<?= CLINIC['phone_raw'] ?>"><?= CLINIC['phone'] ?></a><br><a href="mailto:<?= CLINIC['email'] ?>"><?= CLINIC['email'] ?></a></p>
      <h4>Hours</h4>
      <p>Mon–Fri 9:00 AM – 6:00 PM<br>Sat 9:00 AM – 2:00 PM</p>
    </div>
  </div>
  <div class="wrap footer__legal">
    <p><strong>Areas we serve:</strong> Van Nuys, Encino, Sherman Oaks, Studio City, North Hollywood, Reseda, Tarzana, Lake Balboa and the San Fernando Valley.</p>
    <p>Results vary by individual. Treatments are performed by licensed medical professionals after a consultation. This website is for informational purposes and does not replace medical advice.</p>
    <p>© <?= date('Y') ?> Total Wellness Clinic | LA · Demo site by Majestik Studios</p>
  </div>
</footer>

<a class="float-book" href="<?= app_url('book.php') ?>">Book Now</a>
<script src="<?= $base ?>assets/js/main.js?v=1"></script>
<?php if (!empty($extra_js)) foreach ($extra_js as $js) echo '<script src="' . $base . 'assets/js/' . $js . '?v=1"></script>'; ?>
</body>
</html>
