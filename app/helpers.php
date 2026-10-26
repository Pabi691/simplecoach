<?php
/** Shared helpers for the public site and CRM. */

/** HTML-escape. */
function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

/** Base URL of the public site — auto-detected from the request (works on any host/port),
 *  falling back to SITE_URL on the CLI (e.g. the importer). WEB_BASE is set by each entry point. */
function base_url(): string
{
    static $b = null;
    if ($b !== null) return $b;
    if (defined('WEB_BASE') && !empty($_SERVER['HTTP_HOST'])) {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
              || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
              || (($_SERVER['SERVER_PORT'] ?? '') == '443');
        $b = ($https ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . WEB_BASE;
    } else {
        $b = rtrim(SITE_URL, '/');
    }
    return $b;
}

/** Absolute site URL for a path. */
function url(string $path = ''): string
{
    return base_url() . '/' . ltrim($path, '/');
}

/** Asset URL under /public/assets. */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

/** Public URL for a page/post by slug (pretty URL). */
function page_url(string $slug): string
{
    return ($slug === 'home' || $slug === '') ? url('') : url($slug);
}

/** Resolve a stored menu URL (relative slug or absolute) to a full URL. */
function nav_url(string $u): string
{
    if ($u === '' || $u === 'home' || $u === '/') return url('');
    if (preg_match('#^https?://#', $u)) return $u;
    return url(ltrim($u, '/'));
}

/** Turn a slug into a clean menu label, e.g. coach-hire-london -> "Coach Hire London". */
function nice_label(string $slug): string
{
    $s = ucwords(str_replace('-', ' ', $slug));
    $s = preg_replace('/\bUk\b/', 'UK', $s);
    $s = preg_replace('/\bFaq\b/i', 'FAQ', $s);
    return trim($s);
}

/** All published pages in a hire category, as [[label,url], …] — always complete. */
function hire_links(string $cat): array
{
    static $cache = [];
    if (isset($cache[$cat])) return $cache[$cat];
    $cond = match ($cat) {
        'coach'   => "(title LIKE '%coach hire%' OR title LIKE '%coach and minibus%' OR slug LIKE '%coach-hire%' OR slug LIKE 'coach-to-%' OR slug LIKE 'coaches-to-%')",
        'minibus' => "(title LIKE '%minibus%' OR title LIKE '%mini bus%' OR slug LIKE '%minibus%' OR slug LIKE '%mini-bus%')",
        'airport' => "(slug LIKE '%airport%' OR title LIKE '%airport%' OR slug LIKE '%gatwick%' OR slug LIKE '%heathrow%' OR slug LIKE '%stansted%' OR slug LIKE '%luton%')",
        'europe'  => "(slug IN ('france','spain','germany','italy','netherlands','portugal','poland','belgium','coach-hire-europe','europe-minibus-hire') OR title LIKE '%europe%' OR slug LIKE 'europe-%')",
        'van'     => "(slug LIKE '%van-hire%' OR slug LIKE 'van-hire%' OR title LIKE '%van hire%')",
        default   => "1=0",
    };
    $rows = db_all("SELECT slug, title FROM pages WHERE status='publish' AND $cond ORDER BY slug");
    $out = [];
    foreach ($rows as $r) $out[] = ['label' => nice_label($r['slug']), 'url' => $r['slug']];
    return $cache[$cat] = $out;
}

/** The full primary navigation (top-level + mega dropdowns), built from the live pages. */
function primary_nav(): array
{
    return [
        ['label' => 'Home',         'url' => 'home'],
        ['label' => 'About',        'url' => 'about-us'],
        ['label' => 'Our Fleets',   'url' => 'our-fleets'],
        ['label' => 'Coach Hire',   'url' => 'coach-hire-london', 'items' => hire_links('coach')],
        ['label' => 'Minibus Hire', 'url' => 'minibus-hire-london', 'items' => hire_links('minibus')],
        ['label' => 'Destinations', 'url' => 'service-area', 'columns' => [
            ['heading' => 'Airport Transfers', 'items' => hire_links('airport')],
            ['heading' => 'Europe',            'items' => hire_links('europe')],
            ['heading' => 'Van Hire',          'items' => hire_links('van')],
        ]],
        ['label' => 'Reviews',      'url' => 'review'],
        ['label' => 'FAQ',          'url' => 'faq'],
        ['label' => 'Contact Us',   'url' => 'contact-us'],
    ];
}

/** Cached global settings. */
function settings(): array
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (db_all('SELECT skey, svalue FROM settings') as $r) {
            $cache[$r['skey']] = $r['svalue'];
        }
    }
    return $cache;
}

/** One setting with fallback. */
function setting(string $key, ?string $default = null): ?string
{
    $s = settings();
    return array_key_exists($key, $s) && $s[$key] !== null && $s[$key] !== '' ? $s[$key] : $default;
}

/** Upsert a setting. */
function set_setting(string $key, ?string $value): void
{
    db_run('INSERT INTO settings (skey, svalue) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE svalue = VALUES(svalue)', [$key, $value]);
}

/** Navigation items for a location, as a nested tree. */
function menu_tree(string $location): array
{
    $rows = db_all('SELECT * FROM menus WHERE location = ? ORDER BY sort, id', [$location]);
    $byId = [];
    foreach ($rows as $r) { $r['children'] = []; $byId[$r['id']] = $r; }
    $tree = [];
    foreach ($byId as $id => $r) {
        if ($r['parent_id'] && isset($byId[$r['parent_id']])) {
            $byId[$r['parent_id']]['children'][] = &$byId[$id];
        } else {
            $tree[] = &$byId[$id];
        }
    }
    return $tree;
}

/** Make a URL-safe slug. */
function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

/** Truncate plain text to n chars for meta descriptions. */
function str_excerpt(string $html, int $len = 155): string
{
    $t = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
    if (mb_strlen($t) <= $len) return $t;
    return rtrim(mb_substr($t, 0, $len - 1)) . '…';
}

/** Redirect helper. */
function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}

/** Simple flash messages (CRM). */
function flash(string $msg = null): ?string
{
    if ($msg !== null) { $_SESSION['flash'] = $msg; return null; }
    $m = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $m;
}
