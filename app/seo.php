<?php
/** Render SEO <head> tags from a page/post row + global settings. */

function render_seo(array $o = [], string $type = 'page'): string
{
    $siteName = setting('site_name', SITE_NAME);
    $sep      = ' | ';

    $title = $o['meta_title'] ?? '';
    if ($title === '' || $title === null) {
        $title = ($o['title'] ?? $siteName);
        if (($o['slug'] ?? '') !== 'home') $title .= $sep . $siteName;
    }

    $desc = $o['meta_description'] ?? '';
    if ($desc === '' || $desc === null) {
        $desc = str_excerpt($o['content'] ?? setting('default_meta_description', ''), 155);
    }

    $keywords  = $o['meta_keywords'] ?? setting('default_meta_keywords', '');
    $robots    = $o['robots'] ?? 'index,follow';
    $canonical = $o['canonical_url'] ?? '';
    if ($canonical === '' || $canonical === null) {
        $canonical = isset($o['slug']) ? page_url($o['slug']) : url('');
    }

    $ogTitle = $o['og_title'] ?? $title;
    $ogDesc  = $o['og_description'] ?? $desc;
    $ogImage = $o['og_image'] ?? setting('default_og_image', asset('image/logo.png'));
    if ($ogImage && !preg_match('#^https?://#', $ogImage)) $ogImage = url(ltrim($ogImage, '/'));

    $out  = "<title>" . e($title) . "</title>\n";
    $out .= '<meta name="description" content="' . e($desc) . "\">\n";
    if ($keywords) $out .= '<meta name="keywords" content="' . e($keywords) . "\">\n";
    $out .= '<meta name="robots" content="' . e($robots) . "\">\n";
    $out .= '<link rel="canonical" href="' . e($canonical) . "\">\n";

    // Open Graph / Twitter
    $out .= '<meta property="og:type" content="' . ($type === 'post' ? 'article' : 'website') . "\">\n";
    $out .= '<meta property="og:site_name" content="' . e($siteName) . "\">\n";
    $out .= '<meta property="og:title" content="' . e($ogTitle) . "\">\n";
    $out .= '<meta property="og:description" content="' . e($ogDesc) . "\">\n";
    $out .= '<meta property="og:url" content="' . e($canonical) . "\">\n";
    if ($ogImage) $out .= '<meta property="og:image" content="' . e($ogImage) . "\">\n";
    $out .= '<meta name="twitter:card" content="summary_large_image">' . "\n";

    // Structured data (per-object override, else global org schema)
    $schema = $o['schema_json'] ?? '';
    if ($schema) {
        $out .= '<script type="application/ld+json">' . $schema . "</script>\n";
    }
    if ($global = setting('schema_json', '')) {
        $out .= '<script type="application/ld+json">' . $global . "</script>\n";
    }
    return $out;
}
