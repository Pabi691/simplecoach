<?php
$seo_obj = $post; $seo_type = 'post';
require __DIR__ . '/partials/header.php';
?>
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="<?= url('') ?>">Home</a> / <a href="<?= url('blog') ?>">Blog</a> / <?= e($post['title']) ?></div>
    <span class="eyebrow"><?= e($post['cat_name'] ?: 'Article') ?></span>
    <h1 style="max-width:22ch"><?= e($post['title']) ?></h1>
    <?php if ($post['published_at']): ?><p style="color:rgba(255,255,255,.6)"><?= date('j M Y', strtotime($post['published_at'])) ?></p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ($post['featured_image']): ?>
      <img src="<?= e($post['featured_image']) ?>" alt="<?= e($post['title']) ?>" style="width:100%;max-width:820px;margin:0 auto 30px;border-radius:18px;box-shadow:var(--shadow-sm)" onerror="this.remove()">
    <?php endif; ?>
    <article class="prose"><?= $post['content'] ?></article>

    <?php if ($related): ?>
    <div style="max-width:820px;margin:60px auto 0">
      <h3>Related articles</h3>
      <div class="post-grid" style="grid-template-columns:repeat(3,1fr);margin-top:20px">
        <?php foreach ($related as $r): ?>
        <a class="post-card" href="<?= page_url($r['slug']) ?>">
          <div class="thumb" <?= $r['featured_image'] ? 'style="background-image:url(\'' . e($r['featured_image']) . '\')"' : '' ?>></div>
          <div class="pc-body"><h3 style="font-size:1rem"><?= e($r['title']) ?></h3></div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
