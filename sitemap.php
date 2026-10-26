<?php
/** Dynamic XML sitemap — all published pages & posts. */
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/db.php';
require_once __DIR__ . '/app/helpers.php';

if (!defined('WEB_BASE')) define('WEB_BASE', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'));

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemap.org/schemas/sitemap/0.9">' . "\n";

$rows = array_merge(
    db_all("SELECT slug, updated_at, is_home FROM pages WHERE status='publish' AND robots NOT LIKE 'noindex%'"),
    db_all("SELECT slug, updated_at, 0 AS is_home FROM posts WHERE status='publish' AND robots NOT LIKE 'noindex%'")
);
foreach ($rows as $r) {
    $loc = $r['is_home'] ? url('') : page_url($r['slug']);
    $lastmod = date('Y-m-d', strtotime($r['updated_at'] ?: 'now'));
    $pri = $r['is_home'] ? '1.0' : '0.7';
    echo "  <url><loc>" . e($loc) . "</loc><lastmod>$lastmod</lastmod><priority>$pri</priority></url>\n";
}
echo '</urlset>';
