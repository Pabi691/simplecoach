<?php
$seo_obj = $page; $seo_type = 'page';
require __DIR__ . '/partials/header.php';
?>
<section class="hero" style="min-height:70vh">
  <div class="container center" style="margin:0 auto">
    <h1 style="font-size:clamp(4rem,14vw,9rem);margin-bottom:0">404</h1>
    <h2>Page Not Found</h2>
    <p class="sub" style="margin:0 auto 26px">The page you're looking for doesn't exist or has moved.</p>
    <a class="btn btn-primary btn-lg" href="<?= url('') ?>">Back to Home →</a>
  </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
