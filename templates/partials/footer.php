<?php
$footerNav   = menu_tree('footer');
$destNav     = menu_tree('destinations');
$wa          = setting('whatsapp', SITE_WHATSAPP);
?>
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <a class="brand" href="<?= url('') ?>" style="margin-bottom:16px"><span>Simple<small>Coach Hire</small></span></a>
        <p style="max-width:34ch"><?= e(setting('footer_about','')) ?></p>
        <div class="trustpilot" style="margin-top:14px">
          <span class="stars">★★★★★</span>
          <span style="color:#fff">Trustpilot <?= e(setting('trustpilot_rating','4.8')) ?>/5</span>
        </div>
      </div>
      <div>
        <h4>Contact Us</h4>
        <ul class="footer-contact">
          <li><span class="i">📞</span><a href="tel:<?= e(preg_replace('/\s+/','',setting('phone',SITE_PHONE))) ?>"><?= e(setting('phone',SITE_PHONE)) ?></a></li>
          <li><span class="i">✉</span><a href="mailto:<?= e(setting('email',SITE_EMAIL)) ?>"><?= e(setting('email',SITE_EMAIL)) ?></a></li>
          <li><span class="i">📍</span><span><?= e(setting('address','')) ?></span></li>
        </ul>
      </div>
      <div>
        <h4>Our Destinations</h4>
        <ul>
          <?php foreach ($destNav as $d): ?><li><a href="<?= e(nav_url($d['url'])) ?>"><span class="i">📍</span><?= e($d['label']) ?></a></li><?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h4>Quick Links</h4>
        <ul>
          <?php foreach ($footerNav as $f): ?><li><a href="<?= e(nav_url($f['url'])) ?>"><?= e($f['label']) ?></a></li><?php endforeach; ?>
          <li><a href="<?= url('terms-of-engagement') ?>">Terms of Engagement</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?= date('Y') ?> <?= e(setting('site_name',SITE_NAME)) ?>. All rights reserved.</span>
      <span>Premium coach &amp; minibus hire across the UK.</span>
    </div>
  </div>
</footer>

<?php if ($wa): ?><a class="wa-float" href="https://wa.me/<?= e($wa) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">🟢</a><?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.1.14/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lenis/1.1.13/lenis.min.js"></script>
<script>
  window.SCH_BASE = <?= json_encode(rtrim(SITE_URL,'/')) ?>;
  <?php if (GOOGLE_MAPS_KEY && !str_starts_with(GOOGLE_MAPS_KEY,'PASTE_')): ?>window.SCH_MAPS_KEY = <?= json_encode(GOOGLE_MAPS_KEY) ?>;<?php endif; ?>
</script>
<script src="<?= asset('js/app.js') ?>"></script>
<?= setting('body_end', '') ?>
</body>
</html>
