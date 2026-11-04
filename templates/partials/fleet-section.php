<?php
/* Fleet section — dynamic, managed from the CRM (table `fleets`).
   Each vehicle can have multiple images, shown as a per-card slider.
   Reused on the home page, blog posts, and the Our Fleets page. */
try { $fleets = db_all("SELECT * FROM fleets WHERE status=1 ORDER BY sort, id"); }
catch (Throwable $e) { return; }   // table not created yet -> skip the section quietly
if (!$fleets) return;

$icoSeat = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3Zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z"/></svg>';
$icoAc   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 2v20M3.5 7 20.5 17M20.5 7 3.5 17"/></svg>';
$icoBag  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="7" width="14" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M9 11v5M15 11v5"/></svg>';
$icoWifi = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5a10 10 0 0 1 14 0M8.5 16a5 5 0 0 1 7 0"/><circle cx="12" cy="19.5" r="1" fill="currentColor" stroke="none"/></svg>';
$featIco = ['AC' => $icoAc, 'Luggage' => $icoBag, 'WiFi' => $icoWifi, 'WiFi ' => $icoWifi];
$fleet_src = function ($img) {
    return preg_match('#^https?://#i', $img) ? $img : asset('image/' . ltrim($img, '/'));
};
?>
<section class="section soft" id="fleet">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">Our Fleet</span>
      <h2>Find the Perfect Ride</h2>
      <p class="lead">Modern, comfortable and reliable coaches and minibuses for every group size.</p>
    </div>
    <div class="fleet-grid">
      <?php foreach ($fleets as $fl):
        $imgs  = json_decode($fl['images'] ?? '[]', true);
        if (!is_array($imgs) || !$imgs) $imgs = [asset('image/hero-coach.png')];
        $feats = array_filter(array_map('trim', explode(',', (string)$fl['features'])));
        $seats = $fl['seats'];
      ?>
      <div class="fleet-card reveal">
        <div class="fleet-img">
          <span class="fleet-badge"><?= $icoSeat ?><?= e($seats) ?> Seater</span>
          <div class="fleet-slider swiper">
            <div class="swiper-wrapper">
              <?php foreach ($imgs as $img): ?>
              <div class="swiper-slide"><img src="<?= e($fleet_src($img)) ?>" alt="<?= e($fl['title']) ?>" loading="lazy"></div>
              <?php endforeach; ?>
            </div>
            <?php if (count($imgs) > 1): ?><div class="swiper-pagination"></div><?php endif; ?>
          </div>
        </div>
        <div class="fleet-body">
          <h3 class="fleet-title"><span class="num"><?= e($seats) ?></span> Seater</h3>
          <p class="fleet-tag"><?= e($fl['tagline']) ?></p>
          <div class="fleet-rate"><span class="stars">★★★★★</span><b><?= e($fl['rating']) ?></b></div>
          <div class="fleet-feats">
            <span><i><?= $icoSeat ?></i><?= e($seats) ?> Seats</span>
            <?php foreach ($feats as $ft): ?>
            <span><i><?= $featIco[$ft] ?? $icoAc ?></i><?= e($ft) ?></span>
            <?php endforeach; ?>
          </div>
          <a class="fleet-btn" href="<?= url('our-fleets') ?>"><span class="fb-ic">→</span>View Details</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
