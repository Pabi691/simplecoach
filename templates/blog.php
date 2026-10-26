<?php
$seo_obj = $page; $seo_type = 'page';
require __DIR__ . '/partials/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="<?= url('') ?>">Home</a> / Blog</div>
    <span class="eyebrow">Insights &amp; Guides</span>
    <h1><?= e($page['title'] ?: 'Blog') ?></h1>
    <p class="sub" style="color:rgba(255,255,255,.82)">Travel tips, cost guides and everything about coach &amp; minibus hire in the UK.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if (!$posts): ?>
      <p class="center lead">No posts published yet.</p>
    <?php else: ?>
    <div class="post-grid">
      <?php foreach ($posts as $p): ?>
      <a class="post-card reveal" href="<?= page_url($p['slug']) ?>">
        <div class="thumb" <?= $p['featured_image'] ? 'style="background-image:url(\'' . e($p['featured_image']) . '\')"' : '' ?>></div>
        <div class="pc-body">
          <h3><?= e($p['title']) ?></h3>
          <p><?= e(str_excerpt($p['excerpt'] ?: '', 110)) ?></p>
          <span style="color:var(--royal);font-weight:600;font-size:.88rem;display:inline-block;margin-top:10px">Read more →</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php if ($pages_total > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $pages_total; $i++): ?>
        <?php if ($i == $pg): ?><span class="current"><?= $i ?></span>
        <?php else: ?><a href="<?= url('blog') ?>?paged=<?= $i ?>"><?= $i ?></a><?php endif; ?>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
