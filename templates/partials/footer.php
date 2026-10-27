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
        <?php $tpUrl = setting('trustpilot_url'); $tpTag = $tpUrl ? 'a' : 'div'; ?>
        <<?= $tpTag ?> class="trustpilot" style="margin-top:14px"<?= $tpUrl ? ' href="' . e($tpUrl) . '" target="_blank" rel="noopener"' : '' ?>>
          <span class="stars">★★★★★</span>
          <span style="color:#fff">Trustpilot <?= e(setting('trustpilot_rating','4.8')) ?>/5</span>
        </<?= $tpTag ?>>
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

<?php if ($wa): ?><a class="wa-float" href="https://wa.me/<?= e($wa) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><svg viewBox="0 0 32 32" width="30" height="30" fill="currentColor" aria-hidden="true"><path d="M16.004 0C7.17 0 .01 7.16.01 15.99c0 2.82.74 5.57 2.14 7.99L0 32l8.2-2.14a15.9 15.9 0 0 0 7.8 2h.01c8.83 0 15.99-7.16 15.99-15.99C32.01 7.16 24.84 0 16.004 0zm0 29.2h-.01a13.2 13.2 0 0 1-6.72-1.84l-.48-.29-4.87 1.27 1.3-4.74-.31-.49a13.2 13.2 0 1 1 11.09 6.09zm7.24-9.88c-.4-.2-2.35-1.16-2.71-1.29-.36-.13-.63-.2-.9.2-.26.4-1.03 1.29-1.26 1.55-.23.26-.46.3-.86.1-.4-.2-1.68-.62-3.2-1.97-1.18-1.05-1.98-2.35-2.21-2.75-.23-.4-.02-.61.18-.81.18-.18.4-.46.6-.69.2-.23.26-.4.4-.66.13-.26.07-.49-.03-.69-.1-.2-.9-2.17-1.23-2.97-.32-.76-.65-.66-.9-.67l-.76-.02c-.26 0-.69.1-1.05.5-.36.4-1.38 1.35-1.38 3.28 0 1.94 1.41 3.81 1.61 4.07.2.26 2.78 4.25 6.73 5.96.94.41 1.67.65 2.24.83.94.3 1.8.26 2.48.16.76-.11 2.35-.96 2.68-1.89.33-.92.33-1.72.23-1.89-.1-.17-.36-.26-.76-.46z"/></svg></a><?php endif; ?>

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
