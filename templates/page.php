<?php
$seo_obj = $page; $seo_type = 'page';
require __DIR__ . '/partials/header.php';

/* General / functional pages keep the simple layout (no quote sidebar, no feature photo).
   Everything else is treated as a service/location page and gets the sticky quote form. */
$plainPages = ['about-us','contact-us','our-fleets','faq','review','reviews','service-area',
               'terms-of-engagement','privacy-policy','services','blog'];
$isPlain  = in_array($page['slug'], $plainPages, true);
$phoneTel = preg_replace('/\s+/', '', setting('phone', SITE_PHONE));
$contactEmail = setting('email', SITE_EMAIL);
$whatsAppNumber = preg_replace('/\D+/', '', setting('whatsapp', SITE_WHATSAPP) ?: SITE_WHATSAPP);
?>

<?php if ($isPlain): ?>
<!-- ===== Simple page (About / Contact / Fleets / FAQ / Reviews / etc.) ===== -->
<section class="page-hero<?= in_array($page['slug'], ['about-us', 'contact-us', 'faq'], true) ? ' page-hero--' . e($page['slug']) : '' ?>">
  <div class="container">
    <div class="breadcrumb"><a href="<?= url('') ?>">Home</a> / <?= e($page['title']) ?></div>
    <span class="eyebrow"><?= e(setting('site_name', SITE_NAME)) ?></span>
    <h1><?= e($page['hero_heading'] ?: $page['title']) ?></h1>
    <?php if (!empty($page['hero_subheading'])): ?><p class="sub" style="max-width:62ch;color:rgba(255,255,255,.85)"><?= e($page['hero_subheading']) ?></p><?php endif; ?>
  </div>
</section>
<?php if ($page['slug'] === 'our-fleets'): ?>
  <?php if (trim(strip_tags($page['content'])) !== ''): ?>
  <section class="section"><div class="container"><article class="prose"><?= $page['content'] ?></article></div></section>
  <?php endif; ?>
  <?php require __DIR__ . '/partials/fleet-section.php'; ?>
<?php elseif ($page['slug'] === 'about-us'): ?>
<section class="section special-page about-page">
  <div class="container about-layout">
    <article class="prose special-content about-content"><?= $page['content'] ?></article>
    <aside class="about-visual" style="background-image:url('<?= asset('image/bus1.jpg') ?>')">
      <div class="about-visual-card">
        <span class="about-card-kicker">Simple Coach Hire</span>
        <strong>Group travel, made straightforward.</strong>
        <p>From minibuses to full-size coaches, we help groups travel in comfort across the UK.</p>
      </div>
    </aside>
  </div>
  <div class="container about-values" aria-label="Our service values">
    <div><span class="value-num">01</span><b>Comfort first</b><p>Well-presented vehicles for relaxed journeys.</p></div>
    <div><span class="value-num">02</span><b>Nationwide travel</b><p>Dependable transport wherever your group is headed.</p></div>
    <div><span class="value-num">03</span><b>Clear support</b><p>Helpful people from your first enquiry to arrival.</p></div>
  </div>
</section>

<?php elseif ($page['slug'] === 'contact-us'): ?>
<section class="section special-page contact-page">
  <div class="container contact-layout">
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
    <aside class="contact-panel">
      <span class="eyebrow">Speak to our team</span>
      <h2>We are here to help</h2>
      <p>Choose the contact method that works best for you.</p>
      <a class="contact-channel" href="tel:<?= e($phoneTel) ?>"><span>01</span><div><small>Call us</small><b><?= e(setting('phone', SITE_PHONE)) ?></b></div></a>
      <a class="contact-channel" href="mailto:<?= e($contactEmail) ?>"><span>02</span><div><small>Email us</small><b><?= e($contactEmail) ?></b></div></a>
      <a class="contact-channel" href="https://wa.me/<?= e($whatsAppNumber) ?>" target="_blank" rel="noopener"><span>03</span><div><small>WhatsApp</small><b>Message our team</b></div></a>
    </aside>
  </div>
</section>

<?php elseif ($page['slug'] === 'faq'): ?>
<section class="section special-page faq-page">
  <div class="container faq-layout">
    <article class="prose special-content faq-prose"><?= $page['content'] ?></article>
    <aside class="faq-panel">
      <span class="eyebrow">Still need help?</span>
      <h2>Let’s make your journey easy.</h2>
      <p>Our team can answer questions about vehicles, availability and your itinerary.</p>
      <a class="faq-phone" href="tel:<?= e($phoneTel) ?>">Call <?= e(setting('phone', SITE_PHONE)) ?></a>
      <a class="btn btn-cta" href="<?= url('#book') ?>">Get an Instant Quote <span class="btn-arrow">&rarr;</span></a>
      <a class="faq-contact" href="<?= url('contact-us') ?>">Contact our team</a>
    </aside>
  </div>
</section>

<?php else: ?>
<section class="section">
  <div class="container">
    <article class="prose"><?= $page['content'] ?></article>
    <div class="center" style="margin-top:46px"><a class="btn btn-primary btn-lg" href="<?= url('#book') ?>">Get an Instant Quote →</a></div>
  </div>
</section>
<?php endif; ?>

<?php else: ?>
<!-- ===== Service / location page (banner + feature photo + H1 + sticky quote form) ===== -->
<?php
$bannerImg = asset('image/hero-bg.jpg');
$featImg   = !empty($page['hero_image']) ? $page['hero_image'] : '';
$svgPin  = '<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.9-3.1-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg>';
$svgCal  = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4.5" width="18" height="17" rx="2.5"/><path d="M3 9.5h18M8 2.5v4M16 2.5v4"/></svg>';
$svgPhone= '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';
?>
<section class="page-hero has-img" style="background-image:linear-gradient(118deg,rgba(7,21,45,.93) 0%,rgba(7,21,45,.72) 45%,rgba(10,28,66,.5) 100%),url('<?= e($bannerImg) ?>')">
  <div class="container">
    <div class="breadcrumb"><a href="<?= url('') ?>">Home</a> / <?= e($page['title']) ?></div>
    <span class="eyebrow"><?= e(setting('site_name', SITE_NAME)) ?></span>
    <h4 class="ph-title"><?= e($page['hero_heading'] ?: $page['title']) ?></h4>
    <?php if (!empty($page['hero_subheading'])): ?><p class="sub"><?= e($page['hero_subheading']) ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container page-layout">
    <div class="page-main">
      <?php if ($featImg): ?><img class="page-feat" src="<?= e($featImg) ?>" alt="<?= e($page['title']) ?>" loading="lazy"><?php endif; ?>
      <h1 class="page-title"><?= e($page['hero_heading'] ?: $page['title']) ?></h1>
      <article class="prose"><?= $page['content'] ?></article>
    </div>

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

<?php require __DIR__ . '/partials/fleet-section.php'; ?>
<?php require __DIR__ . '/partials/booking-modal.php'; ?>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
