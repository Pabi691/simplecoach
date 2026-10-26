<?php
/** Public front controller — resolves pretty URLs to pages / posts. */
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/db.php';
require_once __DIR__ . '/app/helpers.php';
require_once __DIR__ . '/app/seo.php';

// URL base = folder of this front controller (…/public), auto-detected.
if (!defined('WEB_BASE')) define('WEB_BASE', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'));

$rawRoute = trim((string)($_GET['route'] ?? ''), '/');
if ($rawRoute === 'sitemap.xml' || $rawRoute === 'sitemap') { require __DIR__ . '/sitemap.php'; exit; }
$route = preg_replace('/[^a-zA-Z0-9\/_-]/', '', $rawRoute);

// ---- 301/302 redirects migrated from Rank Math ----
if ($route !== '') {
    $rd = db_one('SELECT target, type FROM redirects WHERE source = ? LIMIT 1', [$route]);
    if (!$rd) $rd = db_one('SELECT target, type FROM redirects WHERE source = ? LIMIT 1', [$route . '/']);
    if ($rd) {
        $target = preg_match('#^https?://#', $rd['target']) ? $rd['target'] : url($rd['target']);
        http_response_code((int)$rd['type'] ?: 301);
        header('Location: ' . $target);
        exit;
    }
}

function render(string $template, array $vars = []): void
{
    extract($vars);
    require __DIR__ . '/templates/' . $template . '.php';
    exit;
}

// ---- Home ----
if ($route === '' || $route === 'home') {
    $page = db_one("SELECT * FROM pages WHERE (is_home=1 OR slug='home') AND status='publish' LIMIT 1")
            ?: db_one("SELECT * FROM pages WHERE slug='home' LIMIT 1") ?: [];
    render('home', ['page' => $page]);
}

// ---- Blog index ----
if ($route === 'blog') {
    $page = db_one("SELECT * FROM pages WHERE slug='blog' LIMIT 1") ?: ['title' => 'Blog', 'slug' => 'blog'];
    $per  = 12;
    $pg   = max(1, (int)($_GET['paged'] ?? 1));
    $total = (int)db_one("SELECT COUNT(*) c FROM posts WHERE status='publish'")['c'];
    $posts = db_all("SELECT id,slug,title,excerpt,featured_image,published_at
                     FROM posts WHERE status='publish'
                     ORDER BY published_at DESC LIMIT $per OFFSET " . (($pg - 1) * $per));
    render('blog', ['page' => $page, 'posts' => $posts, 'pg' => $pg, 'pages_total' => (int)ceil($total / $per)]);
}

// ---- Page by slug ----
$page = db_one("SELECT * FROM pages WHERE slug = ? AND status='publish' LIMIT 1", [$route]);
if ($page) {
    $tpl = ($page['template'] === 'blog') ? 'blog' : 'page';
    if ($tpl === 'blog') { header('Location: ' . url('blog')); exit; }
    render('page', ['page' => $page]);
}

// ---- Blog post by slug ----
$post = db_one("SELECT p.*, c.name AS cat_name, c.slug AS cat_slug
                FROM posts p LEFT JOIN post_categories c ON c.id=p.category_id
                WHERE p.slug = ? AND p.status='publish' LIMIT 1", [$route]);
if ($post) {
    $related = db_all("SELECT slug,title,featured_image FROM posts
                       WHERE status='publish' AND id <> ? ORDER BY published_at DESC LIMIT 3", [$post['id']]);
    render('post', ['post' => $post, 'related' => $related]);
}

// ---- 404 ----
http_response_code(404);
render('404', ['page' => ['title' => 'Page Not Found', 'slug' => '404', 'robots' => 'noindex,follow']]);
