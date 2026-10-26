<?php
$seo_obj = $page; $seo_type = 'page';
require __DIR__ . '/partials/header.php';

// Banner image: use the page's hero_image if set, otherwise a branded default.
$bannerImg = !empty($page['hero_image']) ? $page['hero_image'] : asset('image/hero-bg.jpg');
$phoneTel  = preg_replace('/\s+/', '', setting('phone', SITE_PHONE));

$svgPin  = '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.9-3.1-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg>';
$svgCal  = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4.5" width="18" height="17" rx="2.5"/><path d="M3 9.5h18M8 2.5v4M16 2.5v4"/></svg>';
$svgPhone= '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';
?>
<section class="page-hero has-img" style="background-image:linear-gradient(118deg,rgba(7,21,45,.93) 0%,rgba(7,21,45,.72) 45%,rgba(10,28,66,.5) 100%),url('<?= e($bannerImg) ?>')">
  <div class="container">
    <div class="breadcrumb"><a href="<?= url('') ?>">Home</a> / <?= e($page['title']) ?></div>
    <span class="eyebrow"><?= e(setting('site_name', SITE_NAME)) ?></span>
    <h1><?= e($page['hero_heading'] ?: $page['title']) ?></h1>
    <?php if (!empty($page['hero_subheading'])): ?><p class="sub"><?= e($page['hero_subheading']) ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container page-layout">
    <article class="prose"><?= $page['content'] ?></article>

    <aside class="page-side">
      <div class="side-quote" id="book">
        <h3 class="sq-h">Get an Instant Quote</h3>
        <p class="sq-sub">Fast, free &amp; no obligation.</p>
        <form id="quoteFormSide" autocomplete="off">
          <div class="qb-in"><span class="i"><?= $svgPin ?></span><input type="text" id="side_pickup_location" name="pickup_location" placeholder="Pickup address" required>
            <input type="hidden" name="pickup_latitude" id="side_pickup_latitude"><input type="hidden" name="pickup_longitude" id="side_pickup_longitude"></div>
          <div class="qb-in"><span class="i"><?= $svgPin ?></span><input type="text" id="side_drop_location" name="dropoff_location" placeholder="Drop-off address" required>
            <input type="hidden" name="dropoff_latitude" id="side_dropoff_latitude"><input type="hidden" name="dropoff_longitude" id="side_dropoff_longitude"></div>
          <div class="qb-in"><span class="i"><?= $svgCal ?></span><input type="date" name="pickup_date" required></div>
          <input type="hidden" name="distance">
          <button class="btn btn-primary btn-cta" type="submit">Get Instant Quote <span class="btn-arrow">→</span></button>
        </form>
        <a class="sq-call" href="tel:<?= e($phoneTel) ?>"><?= $svgPhone ?> or call <b><?= e(setting('phone', SITE_PHONE)) ?></b></a>
      </div>
    </aside>
  </div>
</section>

<?php require __DIR__ . '/partials/booking-modal.php'; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
