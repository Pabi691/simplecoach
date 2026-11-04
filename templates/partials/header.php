<?php
/** Site <head> + two-tier header (logo+contact row, centered nav row) matching the sample. */
$seo_obj  = $seo_obj ?? ($page ?? $post ?? []);
$seo_type = $seo_type ?? (isset($post) ? 'post' : 'page');
$nav = primary_nav();
$phoneRaw = preg_replace('/\s+/', '', setting('phone', SITE_PHONE));
$curSlug  = $seo_obj['slug'] ?? '';
$isContact = ($curSlug === 'contact-us');
$navActive = function ($m) use ($curSlug) {
    $slug = fn($u) => ($u === '' || $u === 'home') ? 'home' : trim($u, '/');
    $cur = $curSlug ?: 'home';
    if ($slug($m['url']) === $cur) return true;
    foreach (($m['items'] ?? []) as $c) if ($slug($c['url']) === $cur) return true;
    foreach (($m['columns'] ?? []) as $col) foreach ($col['items'] as $c) if ($slug($c['url']) === $cur) return true;
    return false;
};
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<?= render_seo($seo_obj, $seo_type) ?>
<?php if ($gid = setting('ga_measurement_id')): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($gid) ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= e($gid) ?>');</script>
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Manrope:wght@400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.1.14/swiper-bundle.min.css">
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">
<link rel="icon" href="<?= asset('image/bus_logo.png') ?>">
<?= setting('global_head', '') ?>
</head>
<body>
<?= setting('body_start', '') ?>

<header class="site-header" id="siteHeader">
  <!-- Row 1: logo + contact + UK Secure badge -->
  <div class="header-top">
    <div class="container">
      <a class="brand" href="<?= url('') ?>" aria-label="<?= e(setting('site_name', SITE_NAME)) ?>">
        <img class="brand-logo" src="<?= asset('image/logo-dark.png') ?>" alt="<?= e(setting('site_name', SITE_NAME)) ?>">
      </a>
      <div class="ht-right">
        <div class="ht-contact">
          <a href="tel:<?= e($phoneRaw) ?>"><span class="i"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span><?= e(setting('phone', SITE_PHONE)) ?></a>
          <a href="mailto:<?= e(setting('email', SITE_EMAIL)) ?>"><span class="i"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2.5"/><path d="m22 7-9.1 5.5a2 2 0 0 1-2.06 0L2 7"/></svg></span><?= e(setting('email', SITE_EMAIL)) ?></a>
        </div>
        <img class="secure-badge" src="<?= asset('image/secure_uk_1.png') ?>" alt="UK Secure Site" onerror="this.remove()">
        <button class="nav-toggle" id="navToggle" aria-label="Menu">☰</button>
      </div>
    </div>
  </div>

  <!-- Row 2: centered nav -->
  <div class="header-main">
    <div class="container">
      <nav class="nav">
        <?php foreach ($nav as $m): $hasMega = !empty($m['items']) || !empty($m['columns']); $act = $navActive($m) ? ' active' : ''; ?>
          <?php if ($hasMega): ?>
            <div class="has-sub<?= $act ?>">
              <a href="<?= e(nav_url($m['url'])) ?>"><?= e($m['label']) ?></a>
              <div class="mega">
                <?php if (!empty($m['items'])): ?>
                  <div class="mega-flow"><?php foreach ($m['items'] as $c): ?><a href="<?= e(nav_url($c['url'])) ?>"><?= e($c['label']) ?></a><?php endforeach; ?></div>
                <?php else: ?>
                  <div class="mega-cols">
                    <?php foreach ($m['columns'] as $col): ?>
                      <div class="mega-col"><h5><?= e($col['heading']) ?></h5>
                        <?php foreach ($col['items'] as $c): ?><a href="<?= e(nav_url($c['url'])) ?>"><?= e($c['label']) ?></a><?php endforeach; ?></div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php else: ?>
            <a class="nav-link<?= $act ?>" href="<?= e(nav_url($m['url'])) ?>"><?= e($m['label']) ?></a>
          <?php endif; ?>
        <?php endforeach; ?>
      </nav>
    </div>
  </div>
</header>

<div class="mobile-nav" id="mobileNav">
  <button class="close" id="navClose" aria-label="Close">×</button>
  <?php foreach ($nav as $m): $kids = $m['items'] ?? null; $cols = $m['columns'] ?? null; ?>
    <?php if ($kids || $cols): ?>
      <details class="m-group">
        <summary><?= e($m['label']) ?></summary>
        <div class="m-sub">
          <?php if ($kids): foreach ($kids as $c): ?><a href="<?= e(nav_url($c['url'])) ?>"><?= e($c['label']) ?></a><?php endforeach; endif; ?>
          <?php if ($cols): foreach ($cols as $col): ?>
            <div class="m-head"><?= e($col['heading']) ?></div>
            <?php foreach ($col['items'] as $c): ?><a href="<?= e(nav_url($c['url'])) ?>"><?= e($c['label']) ?></a><?php endforeach; ?>
          <?php endforeach; endif; ?>
        </div>
      </details>
    <?php else: ?>
      <a href="<?= e(nav_url($m['url'])) ?>"><?= e($m['label']) ?></a>
    <?php endif; ?>
  <?php endforeach; ?>
  <div class="m-contact">
    <a href="tel:<?= e($phoneRaw) ?>"><span class="i">📞</span><?= e(setting('phone', SITE_PHONE)) ?></a>
    <a href="mailto:<?= e(setting('email', SITE_EMAIL)) ?>"><span class="i">✉</span><?= e(setting('email', SITE_EMAIL)) ?></a>
    <img class="secure-badge" src="<?= asset('image/secure_uk_1.png') ?>" alt="UK Secure Site" onerror="this.remove()">
  </div>
</div>

<!-- Sticky quote bar (appears on scroll) -->
<div class="sticky-quote" id="stickyQuote" aria-hidden="true">
  <div class="container">
    <strong class="sq-title">Instant Quote</strong>
    <button class="sticky-quote-open" id="stickyQuoteOpen" type="button" aria-controls="quotePrompt">Get a quote <span aria-hidden="true">→</span></button>
    <form id="quoteFormSticky" autocomplete="off">
      <div class="sq-in"><span class="i">📍</span><input type="text" id="s_pickup_location" name="pickup_location" placeholder="Pickup" required>
        <input type="hidden" name="pickup_latitude" id="s_pickup_latitude"><input type="hidden" name="pickup_longitude" id="s_pickup_longitude"></div>
      <div class="sq-in"><span class="i">📍</span><input type="text" id="s_drop_location" name="dropoff_location" placeholder="Drop-off" required>
        <input type="hidden" name="dropoff_latitude" id="s_dropoff_latitude"><input type="hidden" name="dropoff_longitude" id="s_dropoff_longitude"></div>
      <div class="sq-in"><span class="i">📅</span><input type="date" name="pickup_date" required></div>
      <input type="hidden" name="distance">
      <button class="btn btn-primary btn-cta" type="submit">Get Quote <span class="btn-arrow">→</span></button>
    </form>
  </div>
</div>

<!-- Minimal quote reminder: opened from the mobile sticky bar and every five minutes on the home page. -->
<div class="quote-prompt" id="quotePrompt" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="quotePromptTitle">
  <div class="quote-prompt-card">
    <button class="quote-prompt-close" id="quotePromptClose" type="button" aria-label="Close quote form">×</button>
    <span class="quote-prompt-kicker">Fast &amp; free</span>
    <h2 id="quotePromptTitle">Get an instant quote</h2>
    <p>Tell us where you are travelling and we will take care of the rest.</p>
    <form id="quoteFormPrompt" autocomplete="off">
      <label class="quote-prompt-label" for="p_pickup_location">Pickup address</label>
      <div class="sq-in"><span class="i" aria-hidden="true">●</span><input type="text" id="p_pickup_location" name="pickup_location" placeholder="Enter a location" required>
        <input type="hidden" id="p_pickup_latitude" name="pickup_latitude"><input type="hidden" id="p_pickup_longitude" name="pickup_longitude"></div>
      <label class="quote-prompt-label" for="p_drop_location">Drop-off address</label>
      <div class="sq-in"><span class="i" aria-hidden="true">●</span><input type="text" id="p_drop_location" name="dropoff_location" placeholder="Enter destination" required>
        <input type="hidden" id="p_dropoff_latitude" name="dropoff_latitude"><input type="hidden" id="p_dropoff_longitude" name="dropoff_longitude"></div>
      <label class="quote-prompt-label" for="p_pickup_date">Pickup date</label>
      <div class="sq-in"><span class="i" aria-hidden="true">◫</span><input type="date" id="p_pickup_date" name="pickup_date" required></div>
      <input type="hidden" name="distance">
      <button class="btn btn-cta quote-prompt-submit" type="submit">Continue to quote <span class="btn-arrow">→</span></button>
    </form>
  </div>
</div>
