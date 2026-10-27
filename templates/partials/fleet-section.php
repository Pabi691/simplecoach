<?php
/* Fleet grid — 8 vehicles in two rows of four. Reused on the home page and blog posts.
   Images: assets/image/fleet-{seats}.jpg; falls back to a same-type photo if one is missing. */
$fleet = [
  ['s' => '8',  'n' => '8 Seater',  'fb' => 'fleet-16.jpg'],
  ['s' => '12', 'n' => '12 Seater', 'fb' => 'fleet-16.jpg'],
  ['s' => '16', 'n' => '16 Seater', 'fb' => 'fleet-16.jpg'],
  ['s' => '24', 'n' => '24 Seater', 'fb' => 'fleet-49.jpg'],
  ['s' => '33', 'n' => '33 Seater', 'fb' => 'fleet-49.jpg'],
  ['s' => '49', 'n' => '49 Seater', 'fb' => 'fleet-49.jpg'],
  ['s' => '54', 'n' => '54 Seater', 'fb' => 'fleet-54.jpg'],
  ['s' => '70', 'n' => '70 Seater', 'fb' => 'fleet-70.jpg'],
];
?>
<section class="section soft" id="fleet">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">Our Fleet</span>
      <h2>Find the Perfect Ride</h2>
      <p class="lead">Modern, comfortable and reliable coaches and minibuses for every group size.</p>
    </div>
    <div class="fleet-grid">
      <?php foreach ($fleet as $f): ?>
      <div class="fleet-card reveal">
        <div class="fleet-img"><img src="<?= asset('image/fleet-' . $f['s'] . '.jpg') ?>" alt="<?= e($f['n'] . ' coach hire') ?>" loading="lazy" onerror="this.onerror=null;this.src='<?= asset('image/' . $f['fb']) ?>'"></div>
        <div class="fleet-body">
          <h3><?= e($f['n']) ?></h3>
          <div class="stars">★★★★★</div>
          <a class="btn btn-primary" href="<?= url('our-fleets') ?>">View details <span class="btn-arrow">→</span></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
