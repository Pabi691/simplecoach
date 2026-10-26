<?php
$seo_obj = $page; $seo_type = 'page';
require __DIR__ . '/partials/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="<?= url('') ?>">Home</a> / <?= e($page['title']) ?></div>
    <span class="eyebrow"><?= e(setting('site_name', SITE_NAME)) ?></span>
    <h1><?= e($page['hero_heading'] ?: $page['title']) ?></h1>
    <?php if (!empty($page['hero_subheading'])): ?><p class="sub" style="max-width:60ch;color:rgba(255,255,255,.82)"><?= e($page['hero_subheading']) ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <article class="prose"><?= $page['content'] ?></article>
    <div class="center" style="margin-top:50px">
      <a class="btn btn-primary btn-lg" href="<?= url('#book') ?>">Get an Instant Quote →</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
