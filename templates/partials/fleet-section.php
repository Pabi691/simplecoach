<?php
/* Fleet grid — 8 vehicles in two rows of four. Reused on home, blog posts, and the Our Fleets page.
   Images: assets/image/fleet-{seats}.jpg; falls back to a same-type photo if one is missing. */
$fleet = [
  ['s' => '8',  'fb' => 'fleet-16.jpg'],
  ['s' => '12', 'fb' => 'fleet-16.jpg'],
  ['s' => '16', 'fb' => 'fleet-16.jpg'],
  ['s' => '24', 'fb' => 'fleet-49.jpg'],
  ['s' => '33', 'fb' => 'fleet-49.jpg'],
  ['s' => '49', 'fb' => 'fleet-49.jpg'],
  ['s' => '54', 'fb' => 'fleet-54.jpg'],
  ['s' => '70', 'fb' => 'fleet-70.jpg'],
];
$icoSeat = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3Zm-8 0c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z"/></svg>';
$icoAc   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 2v20M3.5 7 20.5 17M20.5 7 3.5 17"/></svg>';
$icoBag  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="7" width="14" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M9 11v5M15 11v5"/></svg>';
$icoWifi = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5a10 10 0 0 1 14 0M8.5 16a5 5 0 0 1 7 0"/><circle cx="12" cy="19.5" r="1" fill="currentColor" stroke="none"/></svg>';
?>
<section class="section soft" id="fleet">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">Our Fleet</span>
      <h2>Find the Perfect Ride</h2>
      <p class="lead">Modern, comfortable and reliable coaches and minibuses for every group size.</p>
    </div>
    <div class="fleet-grid">
      <?php foreach ($fleet as $f): $type = (int)$f['s'] <= 16 ? 'Minibus' : 'Coach'; ?>
      <div class="fleet-card reveal">
        <div class="fleet-img">
          <span class="fleet-badge"><?= $icoSeat ?><?= e($f['s']) ?> Seater</span>
          <img src="<?= asset('image/fleet-' . $f['s'] . '.jpg') ?>" alt="<?= e($f['s'] . ' seater ' . $type . ' hire') ?>" loading="lazy" onerror="this.onerror=null;this.src='<?= asset('image/' . $f['fb']) ?>'">
        </div>
        <div class="fleet-body">
          <h3 class="fleet-title"><span class="num"><?= e($f['s']) ?></span> Seater</h3>
          <p class="fleet-tag">Spacious &middot; Comfortable &middot; Reliable</p>
          <div class="fleet-rate"><span class="stars">★★★★★</span><b>5.0</b></div>
          <div class="fleet-feats">
            <span><i><?= $icoSeat ?></i><?= e($f['s']) ?> Seats</span>
            <span><i><?= $icoAc ?></i>AC</span>
            <span><i><?= $icoBag ?></i>Luggage</span>
            <span><i><?= $icoWifi ?></i>WiFi</span>
          </div>
          <a class="fleet-btn" href="<?= url('our-fleets') ?>"><span class="fb-ic">→</span>View Details</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
