<?php
$translations = require __DIR__ . '/lang.php';
$supportedLanguages = array_keys($translations);
$fallbackLanguage = 'it';

function detect_language(array $supportedLanguages, string $fallbackLanguage): string
{
  $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
  $firstSegment = explode('/', $path)[0] ?? '';

  if (in_array($firstSegment, $supportedLanguages, true)) {
    return $firstSegment;
  }

  $queryLanguage = $_GET['lang'] ?? '';
  if (in_array($queryLanguage, $supportedLanguages, true)) {
    return $queryLanguage;
  }

  return $fallbackLanguage;
}

function detect_page(array $supportedLanguages): string
{
  $allowedPages = ['features', 'pricing', 'roadmap', 'changelog', 'docs', 'privacy', 'terms', 'blog', 'about'];
  $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
  $segments = $path === '' ? [] : explode('/', $path);
  $firstSegment = $segments[0] ?? '';
  $page = in_array($firstSegment, $supportedLanguages, true) ? ($segments[1] ?? 'home') : ($segments[0] ?? 'home');

  return in_array($page, $allowedPages, true) ? $page : 'home';
}

$lang = detect_language($supportedLanguages, $fallbackLanguage);
$page = detect_page($supportedLanguages);
$baseUrl = 'https://trackerslens.com';
$canonical = $baseUrl . '/' . $lang . '/' . ($page === 'home' ? '' : $page . '/');
$assetVersion = '20260514-07';
$siteName = 'Trackers Lens';
$apiBaseUrl = 'https://api.trackerslens.com';
$appBaseUrl = 'https://app.trackerslens.com';
$seoAuthor = 'Trackers Lens';
$seoKeywords = 'trackers lens, AI dashboard, browser runtime, local data platform, websocket dashboard, API monitoring, RSS monitoring, AI agents, boxTracker, boxLens, local analytics, privacy first dashboard, realtime monitoring, browser automation, data orchestration';
$ogImage = $baseUrl . '/assets/seo/trackers-lens-og.jpg';
$ogImageAlt = 'Trackers Lens AI-powered local data monitoring platform preview';

function value_for_key(array $source, string $key)
{
  $value = $source;
  foreach (explode('.', $key) as $part) {
    if (!is_array($value) || !array_key_exists($part, $value)) {
      return null;
    }
    $value = $value[$part];
  }
  return $value;
}

function t(string $key): string
{
  global $translations, $lang, $fallbackLanguage;
  $value = value_for_key($translations[$lang] ?? [], $key);
  if ($value === null || $value === '') {
    $value = value_for_key($translations[$fallbackLanguage], $key);
  }
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function tv(string $key): string
{
  global $translations, $lang, $fallbackLanguage;
  $value = value_for_key($translations[$lang] ?? [], $key);
  if ($value === null || $value === '') {
    $value = value_for_key($translations[$fallbackLanguage], $key);
  }
  return (string) $value;
}

function lang_url(string $language, string $anchor = ''): string
{
  global $page;
  return '/' . $language . '/' . ($page === 'home' ? '' : $page . '/') . $anchor;
}

function page_url(string $pageName, string $anchor = ''): string
{
  global $lang;
  return '/' . $lang . '/' . ($pageName === 'home' ? '' : $pageName . '/') . $anchor;
}

function icon(string $name, string $class = ''): string
{
  $icons = [
    'login' => '<path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M14 4h4a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-4"/>',
    'notify' => '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/><path d="M19.5 4.5 21 3"/><path d="M4.5 4.5 3 3"/>',
    'play' => '<path d="m8 5 11 7-11 7V5Z"/>',
    'encrypted' => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/><path d="M12 15v2"/>',
    'database' => '<ellipse cx="12" cy="5" rx="7" ry="3"/><path d="M5 5v6c0 1.7 3.1 3 7 3s7-1.3 7-3V5"/><path d="M5 11v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6"/>',
    'code' => '<path d="m9 18-6-6 6-6"/><path d="m15 6 6 6-6 6"/>',
    'hub' => '<circle cx="12" cy="12" r="3"/><circle cx="5" cy="5" r="2"/><circle cx="19" cy="5" r="2"/><circle cx="5" cy="19" r="2"/><circle cx="19" cy="19" r="2"/><path d="m7 7 3 3"/><path d="m17 7-3 3"/><path d="m7 17 3-3"/><path d="m17 17-3-3"/>',
    'brain' => '<path d="M9 4a3 3 0 0 0-3 3 3 3 0 0 0-2 5 3 3 0 0 0 3 5h2V4Z"/><path d="M15 4a3 3 0 0 1 3 3 3 3 0 0 1 2 5 3 3 0 0 1-3 5h-2V4Z"/><path d="M9 9H6"/><path d="M15 9h3"/><path d="M9 14H6"/><path d="M15 14h3"/>',
    'bolt' => '<path d="M13 2 4 14h7l-1 8 10-13h-7l0-7Z"/>',
    'monitoring' => '<path d="M4 19V5"/><path d="M4 19h16"/><path d="m7 15 4-5 4 3 5-7"/>',
    'shield' => '<path d="M12 3 20 6v6c0 5-3.4 8-8 9-4.6-1-8-4-8-9V6l8-3Z"/><path d="M9 12l2 2 4-5"/>',
    'extension' => '<path d="M9 3h6v5h3a3 3 0 1 1 0 6h-3v7H9v-7H6a3 3 0 1 1 0-6h3V3Z"/>',
    'book' => '<path d="M4 5a3 3 0 0 1 3-3h13v17H7a3 3 0 0 0-3 3V5Z"/><path d="M4 19a3 3 0 0 1 3-3h13"/>',
    'api' => '<path d="M4 12h4"/><path d="M16 12h4"/><path d="M9 7l-3 5 3 5"/><path d="m15 7 3 5-3 5"/><path d="m13 6-2 12"/>',
    'groups' => '<circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.4"/><path d="M3 20a6 6 0 0 1 12 0"/><path d="M14 19a5 5 0 0 1 7 0"/>',
    'github' => '<path d="M12 2a10 10 0 0 0-3 19c.5.1.7-.2.7-.5v-2c-3 .7-3.6-1.2-3.6-1.2-.5-1.1-1.1-1.4-1.1-1.4-.9-.6.1-.6.1-.6 1 0 1.6 1.1 1.6 1.1.9 1.6 2.5 1.1 3 .9.1-.7.4-1.1.7-1.4-2.4-.3-5-1.2-5-5.3 0-1.2.4-2.1 1.1-2.9-.1-.3-.5-1.4.1-2.9 0 0 .9-.3 3 1.1a10 10 0 0 1 5.4 0c2.1-1.4 3-1.1 3-1.1.6 1.5.2 2.6.1 2.9.7.8 1.1 1.7 1.1 2.9 0 4.1-2.6 5-5 5.3.4.3.8 1 .8 2v2.6c0 .3.2.6.8.5A10 10 0 0 0 12 2Z"/>',
    'twitter' => '<path d="M4 4 20 20"/><path d="M20 4 4 20"/>',
    'discord' => '<path d="M7 8c3-1 7-1 10 0l1.5 8c-3 2-10 2-13 0L7 8Z"/><path d="M9 14h.01"/><path d="M15 14h.01"/>',
    'youtube' => '<path d="M21 12s0-4-1-5c-1-1-8-1-8-1s-7 0-8 1-1 5-1 5 0 4 1 5 8 1 8 1 7 0 8-1 1-5 1-5Z"/><path d="m10 9 5 3-5 3V9Z"/>',
    'map' => '<path d="M9 18 3 21V6l6-3 6 3 6-3v15l-6 3-6-3Z"/><path d="M9 3v15"/><path d="M15 6v15"/>',
    'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    'check' => '<path d="M20 6 9 17l-5-5"/>',
    'rocket' => '<path d="M4.5 16.5c-1.2 1.2-1.5 3-.9 3.9.9.6 2.7.3 3.9-.9"/><path d="M9 15 4 20"/><path d="M15 9l-6 6"/><path d="M14 4h6v6c0 5-4 9-10 11 2-6 6-10 11-10Z"/><path d="M15 9h.01"/>',
    'mail' => '<path d="M4 6h16v12H4z"/><path d="m4 7 8 6 8-6"/>',
  ];
  $path = $icons[$name] ?? $icons['code'];
  $classes = trim('tl-icon ' . $class);
  return '<span class="' . htmlspecialchars($classes, ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false">' . $path . '</svg></span>';
}

$jsonLd = [
  '@context' => 'https://schema.org',
  '@graph' => [
    [
      '@type' => 'Organization',
      '@id' => $baseUrl . '/#organization',
      'name' => $siteName,
      'url' => $baseUrl . '/',
      'logo' => $baseUrl . '/assets/icons/logo128.png',
      'sameAs' => [
        'https://github.com/trackerslens',
        'https://x.com/trackerslens',
      ],
    ],
    [
      '@type' => 'WebSite',
      '@id' => $baseUrl . '/#website',
      'url' => $baseUrl . '/',
      'name' => $siteName,
      'description' => tv('meta.description'),
      'publisher' => ['@id' => $baseUrl . '/#organization'],
      'inLanguage' => $lang,
    ],
    [
      '@type' => 'SoftwareApplication',
      '@id' => $baseUrl . '/#software',
      'name' => $siteName,
      'applicationCategory' => 'DeveloperApplication',
      'operatingSystem' => 'Chrome, Chromium, Browser Extension',
      'url' => $canonical,
      'description' => tv('meta.description'),
      'image' => $ogImage,
      'offers' => [
        '@type' => 'Offer',
        'price' => '0',
        'priceCurrency' => 'USD',
      ],
      'publisher' => ['@id' => $baseUrl . '/#organization'],
    ],
  ],
];
?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="index, follow">
  <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <meta name="author" content="<?= htmlspecialchars($seoAuthor, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="keywords" content="<?= htmlspecialchars($seoKeywords, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="description" content="<?= $page === 'home' ? t('meta.description') : t('pages.' . $page . '.metaDescription') ?>">
  <meta name="theme-color" content="#060811">
  <meta name="color-scheme" content="dark">
  <meta name="application-name" content="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="apple-mobile-web-app-title" content="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="msapplication-TileColor" content="#060811">
  <meta name="referrer" content="strict-origin-when-cross-origin">
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="alternate" hreflang="it" href="<?= $baseUrl ?>/it/">
  <link rel="alternate" hreflang="en" href="<?= $baseUrl ?>/en/">
  <link rel="alternate" hreflang="es" href="<?= $baseUrl ?>/es/">
  <link rel="alternate" hreflang="x-default" href="<?= $baseUrl ?>/it/">
  <link rel="sitemap" type="application/xml" href="<?= $baseUrl ?>/sitemap.xml">
  <link rel="image_src" href="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale" content="<?= t('locale') ?>">
  <meta property="og:locale:alternate" content="en_US">
  <meta property="og:locale:alternate" content="es_ES">
  <meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:title" content="<?= $page === 'home' ? t('meta.title') : t('pages.' . $page . '.metaTitle') ?>">
  <meta property="og:description" content="<?= $page === 'home' ? t('meta.description') : t('pages.' . $page . '.metaDescription') ?>">
  <meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image:secure_url" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image:type" content="image/png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="<?= htmlspecialchars($ogImageAlt, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:domain" content="trackerslens.com">
  <meta name="twitter:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:title" content="<?= $page === 'home' ? t('meta.title') : t('pages.' . $page . '.metaTitle') ?>">
  <meta name="twitter:description" content="<?= $page === 'home' ? t('meta.description') : t('pages.' . $page . '.metaDescription') ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image:alt" content="<?= htmlspecialchars($ogImageAlt, ENT_QUOTES, 'UTF-8') ?>">
  <title><?= $page === 'home' ? t('meta.title') : t('pages.' . $page . '.metaTitle') ?></title>
  <link rel="icon" href="/assets/icons/logo.svg" type="image/svg+xml">
  <link rel="apple-touch-icon" href="/assets/icons/logo128.png">
  <link rel="manifest" href="/site.webmanifest">
  <link rel="stylesheet" href="/assets/css/style.min.css?v=<?= $assetVersion ?>">
  <script type="application/ld+json">
    <?= json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
  </script>
  <script src="/assets/js/main.min.js?v=<?= $assetVersion ?>" defer></script>
</head>

<body>
  <script>
    window.TrackersLensConfig = {
      apiBaseUrl: "<?= htmlspecialchars($apiBaseUrl, ENT_QUOTES, 'UTF-8') ?>",
      appBaseUrl: "<?= htmlspecialchars($appBaseUrl, ENT_QUOTES, 'UTF-8') ?>",
      currentLanguage: "<?= htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') ?>",
      labels: {
        loginSubmit: <?= json_encode(tv('auth.loginSubmit'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        registerSubmit: <?= json_encode(tv('auth.registerSubmit'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        connecting: <?= json_encode(tv('auth.connecting'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        failed: <?= json_encode(tv('auth.failed'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        success: <?= json_encode(tv('auth.success'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        saving: <?= json_encode(tv('auth.saving'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        newsletterSaved: <?= json_encode(tv('auth.newsletterSaved'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        launchInvalid: <?= json_encode(tv('launch.invalid'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        contactSuccess: <?= json_encode(tv('contact.success'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        contactInvalid: <?= json_encode(tv('contact.invalid'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
      }
    };
  </script>
  <a class="skip-link" href="#main"><?= t('accessibility.skip') ?></a>

  <header class="site-header" data-header>
    <nav class="navbar container" aria-label="Primary navigation">
      <a class="brand" href="<?= page_url('home') ?>#hero" aria-label="Trackers Lens home">
        <img class="brand-mark" src="/assets/icons/logo.svg" alt="" aria-hidden="true" width="42" height="42" fetchpriority="high" decoding="async">
        <span class="brand-name"><span>Trackers</span> <strong>Lens</strong></span>
        <span class="brand-arrow" aria-hidden="true">›</span>
      </a>

      <button class="nav-toggle" type="button" aria-label="Apri menu" aria-expanded="false" aria-controls="site-menu" data-menu-toggle>
        <span></span>
        <span></span>
        <span></span>
      </button>

      <div class="nav-panel" id="site-menu" data-menu>
        <ul class="nav-links">
          <li><a href="<?= page_url('features') ?>"><?= t('nav.features') ?></a></li>
          <li><a href="<?= page_url('home') ?>#why"><?= t('nav.why') ?></a></li>
          <li><a href="<?= page_url('home') ?>#marketplace"><?= t('nav.marketplace') ?></a></li>
          <li><a href="<?= page_url('pricing') ?>"><?= t('nav.pricing') ?></a></li>
          <li><a href="<?= page_url('docs') ?>"><?= t('nav.docs') ?></a></li>
        </ul>

        <div class="nav-actions">
          <label class="language-select" aria-label="<?= t('accessibility.language') ?>">
            <span class="sr-only"><?= t('accessibility.language') ?></span>
            <select data-language-switcher aria-label="Language switcher">
              <option value="<?= lang_url('it') ?>" <?= $lang === 'it' ? 'selected' : '' ?>>IT</option>
              <option value="<?= lang_url('en') ?>" <?= $lang === 'en' ? 'selected' : '' ?>>EN</option>
              <option value="<?= lang_url('es') ?>" <?= $lang === 'es' ? 'selected' : '' ?>>ES</option>
            </select>
          </label>
          <button class="btn btn-ghost" type="button" data-login-open><?= icon('login') ?><?= t('nav.login') ?></button>
          <button class="btn btn-primary" type="button" data-launch-open><?= icon('notify') ?><?= t('nav.download') ?></button>
        </div>
      </div>
    </nav>
  </header>

  <main id="main">
    <?php if ($page === 'home'): ?>
      <section class="hero section" id="hero">
        <div class="container hero-grid">
          <div class="hero-copy reveal">
            <div class="eyebrow"><?= t('hero.badge') ?></div>
            <h1><?= t('hero.title') ?></h1>
            <p class="hero-lead"><?= t('hero.subtitle') ?></p>
            <div class="launch-status" aria-label="<?= t('hero.devLabel') ?>">
              <div class="launch-status-top">
                <span class="launch-pulse" aria-hidden="true"></span>
                <strong><?= t('hero.devLabel') ?></strong>
                <span><?= t('hero.devStatus') ?></span>
              </div>
              <div class="launch-progress" role="progressbar" aria-label="<?= t('hero.devProgress') ?>" aria-valuemin="0" aria-valuemax="100" aria-valuenow="72">
                <i></i>
              </div>
              <p><?= t('hero.devBody') ?></p>
            </div>
            <div class="cta-row">
              <button class="btn btn-primary btn-large" type="button" data-launch-open><?= icon('notify') ?><?= t('hero.ctaPrimary') ?></button>
              <a class="btn btn-secondary btn-large" href="#demo"><?= icon('play') ?><?= t('hero.ctaSecondary') ?></a>
            </div>
            <ul class="trust-list" aria-label="Trust signals">
              <li><?= icon('encrypted', 'trust-icon') ?><strong><?= t('hero.trustPrivate') ?></strong></li>
              <li><?= icon('database', 'trust-icon') ?><strong><?= t('hero.trustLocal') ?></strong></li>
              <li><?= icon('code', 'trust-icon') ?><strong><?= t('hero.trustOpen') ?></strong></li>
            </ul>
          </div>

          <div class="hero-visual reveal" id="demo" aria-label="Trackers Lens dashboard mockup">
            <div class="dashboard-shell">
              <div class="window-bar">
                <span class="brand-mini">Trackers Lens</span>
                <span class="window-dot"></span>
              </div>
              <div class="dashboard-layout">
                <aside class="mock-sidebar" aria-hidden="true">
                  <span></span><span></span><span></span><span></span><span></span>
                </aside>
                <div class="mock-content">
                  <div class="metric-grid">
                    <article class="metric-card"><span><?= t('hero.metricTrackers') ?></span><strong>24</strong><small>+8.7%</small></article>
                    <article class="metric-card"><span><?= t('hero.metricData') ?></span><strong>2.4M</strong><small>+12%</small></article>
                    <article class="metric-card"><span><?= t('hero.metricInsights') ?></span><strong>152</strong><small>+31%</small></article>
                    <article class="metric-card"><span><?= t('hero.metricActions') ?></span><strong>37</strong><small>+6%</small></article>
                  </div>
                  <div class="chart-grid">
                    <div class="chart-card large">
                      <span><?= t('hero.chartActivity') ?></span>
                      <div class="line-chart" aria-hidden="true"></div>
                    </div>
                    <div class="chart-card">
                      <span><?= t('hero.chartResources') ?></span>
                      <div class="bar-chart" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i></div>
                    </div>
                  </div>
                  <div class="tracker-row" aria-hidden="true">
                    <span>BTC Price</span><span>News Monitor</span><span>Twitter Trend</span><span>Portfolio</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="section" id="features">
        <div class="container">
          <div class="section-heading reveal">
            <h2><?= t('value.title') ?></h2>
            <p><?= t('value.subtitle') ?></p>
          </div>
          <div class="feature-grid">
            <article class="feature-card reveal accent-cyan"><?= icon('hub', 'icon-box') ?>
              <h3><?= t('value.cards.monitor.title') ?></h3>
              <p><?= t('value.cards.monitor.body') ?></p>
            </article>
            <article class="feature-card reveal accent-purple"><?= icon('brain', 'icon-box') ?>
              <h3><?= t('value.cards.ai.title') ?></h3>
              <p><?= t('value.cards.ai.body') ?></p>
            </article>
            <article class="feature-card reveal accent-gold"><?= icon('bolt', 'icon-box') ?>
              <h3><?= t('value.cards.automation.title') ?></h3>
              <p><?= t('value.cards.automation.body') ?></p>
            </article>
            <article class="feature-card reveal accent-cyan"><?= icon('monitoring', 'icon-box') ?>
              <h3><?= t('value.cards.visual.title') ?></h3>
              <p><?= t('value.cards.visual.body') ?></p>
            </article>
            <article class="feature-card reveal accent-green"><?= icon('shield', 'icon-box') ?>
              <h3><?= t('value.cards.privacy.title') ?></h3>
              <p><?= t('value.cards.privacy.body') ?></p>
            </article>
            <article class="feature-card reveal accent-purple"><?= icon('extension', 'icon-box') ?>
              <h3><?= t('value.cards.open.title') ?></h3>
              <p><?= t('value.cards.open.body') ?></p>
            </article>
          </div>
        </div>
      </section>

      <section class="section" id="why">
        <div class="container privacy-grid">
          <article class="privacy-panel reveal">
            <div class="server-visual" role="img" aria-label="Local encrypted runtime visualization">
              <div class="server-lines" aria-hidden="true"></div>
              <span class="privacy-badge"><?= t('privacy.badge') ?></span>
              <h2><?= t('privacy.title') ?></h2>
            </div>
          </article>
          <div class="privacy-content reveal">
            <p><?= t('privacy.body') ?></p>
            <div class="privacy-points">
              <div><strong><?= t('privacy.pointCloud.title') ?></strong><span><?= t('privacy.pointCloud.body') ?></span></div>
              <div><strong><?= t('privacy.pointRuntime.title') ?></strong><span><?= t('privacy.pointRuntime.body') ?></span></div>
              <div><strong><?= t('privacy.pointDb.title') ?></strong><span><?= t('privacy.pointDb.body') ?></span></div>
              <div><strong><?= t('privacy.pointApi.title') ?></strong><span><?= t('privacy.pointApi.body') ?></span></div>
            </div>
          </div>
        </div>
      </section>

      <section class="section" id="marketplace">
        <div class="container">
          <div class="section-heading reveal">
            <h2><?= t('architecture.title') ?></h2>
          </div>
          <div class="pillar-grid">
            <article class="pillar-card reveal">
              <h3><?= t('architecture.site.title') ?></h3>
              <p><?= t('architecture.site.body') ?></p>
            </article>
            <article class="pillar-card reveal">
              <h3><?= t('architecture.plugin.title') ?></h3>
              <p><?= t('architecture.plugin.body') ?></p>
            </article>
            <article class="pillar-card reveal">
              <h3><?= t('architecture.chromium.title') ?></h3>
              <p><?= t('architecture.chromium.body') ?></p>
            </article>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="container box-grid">
          <div class="box-copy reveal">
            <h2><?= t('boxes.title') ?></h2>
            <p><?= t('boxes.body') ?></p>
          </div>
          <div class="data-flow reveal" aria-label="Data flow diagram">
            <span>API</span><i>→</i><span>boxTracker</span><i>→</i><span>AI Agent</span><i>→</i><span>boxLens</span>
          </div>
        </div>
      </section>

      <section class="section" id="docs">
        <div class="container developer-grid">
          <div class="developer-copy reveal">
            <h2><?= t('developers.title') ?></h2>
            <p><?= t('developers.body') ?></p>
            <pre><code>const tracker = new Tracker({
  name: "BTC Price",
  source: "wss://api.binance.com/ticker",
  interval: "10s",
  output: "btc-price"
});

tracker.start();</code></pre>
          </div>
          <div class="resource-grid reveal">
            <a href="<?= $canonical ?>#docs" class="resource-card"><?= icon('book') ?><strong><?= t('developers.docs') ?></strong></a>
            <a href="<?= $apiBaseUrl ?>/docs/api-contract" class="resource-card"><?= icon('api') ?><strong><?= t('developers.api') ?></strong></a>
            <a href="<?= $canonical ?>#demo" class="resource-card"><?= icon('code') ?><strong><?= t('developers.examples') ?></strong></a>
            <a href="https://github.com/trackerslens" class="resource-card" target="_blank" rel="noopener noreferrer"><?= icon('groups') ?><strong><?= t('developers.community') ?></strong></a>
          </div>
        </div>
      </section>

      <section class="section stats-section">
        <div class="container stats-grid reveal">
          <div><strong>10K+</strong><span><?= t('stats.users') ?></span></div>
          <div><strong>50K+</strong><span><?= t('stats.trackers') ?></span></div>
          <div><strong>2.4M+</strong><span><?= t('stats.data') ?></span></div>
          <div><strong>99.9%</strong><span><?= t('stats.uptime') ?></span></div>
        </div>
      </section>

      <section class="section" id="pricing">
        <div class="container">
          <div class="section-heading reveal">
            <h2><?= t('pricing.title') ?></h2>
            <p><?= t('pricing.subtitle') ?></p>
          </div>
          <div class="pricing-grid">
            <article class="pricing-card reveal"><span><?= t('pricing.free.label') ?></span><strong><?= t('pricing.free.price') ?></strong>
              <p><?= t('pricing.free.body') ?></p>
            </article>
            <article class="pricing-card featured reveal"><span><?= t('pricing.pro.label') ?></span><strong><?= t('pricing.pro.price') ?></strong>
              <p><?= t('pricing.pro.body') ?></p>
            </article>
            <article class="pricing-card reveal"><span><?= t('pricing.enterprise.label') ?></span><strong><?= t('pricing.enterprise.price') ?></strong>
              <p><?= t('pricing.enterprise.body') ?></p>
            </article>
          </div>
        </div>
      </section>

      <section class="final-cta section" id="download">
        <div class="container reveal">
          <h2><?= t('cta.title') ?></h2>
          <div class="cta-row center">
            <button class="btn btn-primary btn-large" type="button" data-launch-open><?= icon('notify') ?><?= t('cta.download') ?></button>
            <a class="btn btn-secondary btn-large" href="<?= page_url('home') ?>#demo"><?= icon('play') ?><?= t('cta.demo') ?></a>
          </div>
          <p><?= t('cta.note') ?></p>
        </div>
      </section>
    <?php elseif ($page === 'features'): ?>
      <section class="inner-hero section">
        <div class="container inner-hero-grid">
          <div class="reveal">
            <div class="eyebrow"><?= t('pages.features.badge') ?></div>
            <h1><?= t('pages.features.title') ?></h1>
            <p class="hero-lead"><?= t('pages.features.subtitle') ?></p>
            <div class="cta-row">
              <button class="btn btn-primary btn-large" type="button" data-launch-open><?= icon('notify') ?><?= t('hero.ctaPrimary') ?></button>
              <a class="btn btn-secondary btn-large" href="<?= page_url('roadmap') ?>"><?= icon('map') ?><?= t('footer.roadmap') ?></a>
            </div>
          </div>
          <div class="product-console reveal" aria-label="Trackers Lens plugin feature map">
            <div class="console-top"><span>Runtime Plugin</span><strong>Local First</strong></div>
            <div class="console-map">
              <span>Library</span><span>Workspace</span><span>boxLens</span><span>boxTracker</span><span>Monitor</span><span>AI Runtime</span><span>IndexedDB</span><span>Connections</span>
            </div>
          </div>
        </div>
      </section>

      <section class="section">
        <div class="container feature-detail-grid">
          <article class="feature-card reveal accent-cyan"><?= icon('extension', 'icon-box') ?><h3><?= t('pages.features.cards.plugin.title') ?></h3>
            <p><?= t('pages.features.cards.plugin.body') ?></p>
          </article>
          <article class="feature-card reveal accent-purple"><?= icon('monitoring', 'icon-box') ?><h3><?= t('pages.features.cards.workspace.title') ?></h3>
            <p><?= t('pages.features.cards.workspace.body') ?></p>
          </article>
          <article class="feature-card reveal accent-gold"><?= icon('code', 'icon-box') ?><h3><?= t('pages.features.cards.lens.title') ?></h3>
            <p><?= t('pages.features.cards.lens.body') ?></p>
          </article>
          <article class="feature-card reveal accent-green"><?= icon('hub', 'icon-box') ?><h3><?= t('pages.features.cards.tracker.title') ?></h3>
            <p><?= t('pages.features.cards.tracker.body') ?></p>
          </article>
          <article class="feature-card reveal accent-cyan"><?= icon('database', 'icon-box') ?><h3><?= t('pages.features.cards.database.title') ?></h3>
            <p><?= t('pages.features.cards.database.body') ?></p>
          </article>
          <article class="feature-card reveal accent-purple"><?= icon('brain', 'icon-box') ?><h3><?= t('pages.features.cards.ai.title') ?></h3>
            <p><?= t('pages.features.cards.ai.body') ?></p>
          </article>
        </div>
      </section>

      <section class="section">
        <div class="container app-future-grid">
          <div class="box-copy reveal">
            <h2><?= t('pages.features.futureTitle') ?></h2>
            <p><?= t('pages.features.futureBody') ?></p>
          </div>
          <div class="timeline-card reveal">
            <div><strong>1</strong><span>Browser plugin</span></div>
            <div><strong>2</strong><span>Dashboard cloud</span></div>
            <div><strong>3</strong><span>App runtime dedicata</span></div>
          </div>
        </div>
      </section>
    <?php elseif ($page === 'pricing'): ?>
      <section class="inner-hero section">
        <div class="container pricing-hero reveal">
          <div class="eyebrow"><?= t('pages.pricing.badge') ?></div>
          <h1><?= t('pages.pricing.title') ?></h1>
          <p class="hero-lead"><?= t('pages.pricing.subtitle') ?></p>
          <div class="launch-status pricing-progress" aria-label="<?= t('hero.devLabel') ?>">
            <div class="launch-status-top"><span class="launch-pulse" aria-hidden="true"></span><strong><?= t('pages.pricing.progressLabel') ?></strong><span><?= t('pages.pricing.progressStatus') ?></span></div>
            <div class="launch-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="42"><i></i></div>
            <p><?= t('pages.pricing.progressBody') ?></p>
          </div>
        </div>
      </section>
      <section class="section">
        <div class="container pricing-grid page-pricing-grid">
          <article class="pricing-card reveal"><span><?= t('pricing.free.label') ?></span><strong><?= t('pricing.free.price') ?></strong>
            <p><?= t('pages.pricing.freeBody') ?></p><button class="btn btn-secondary" type="button" data-launch-open><?= t('cta.download') ?></button>
          </article>
          <article class="pricing-card featured reveal"><span><?= t('pricing.pro.label') ?></span><strong><?= t('pricing.pro.price') ?></strong>
            <p><?= t('pages.pricing.proBody') ?></p><button class="btn btn-primary" type="button" data-contact-open><?= t('pages.pricing.contact') ?></button>
          </article>
          <article class="pricing-card reveal"><span><?= t('pricing.enterprise.label') ?></span><strong><?= t('pricing.enterprise.price') ?></strong>
            <p><?= t('pages.pricing.enterpriseBody') ?></p><button class="btn btn-secondary" type="button" data-contact-open><?= t('pages.pricing.contact') ?></button>
          </article>
        </div>
      </section>
    <?php elseif ($page === 'roadmap'): ?>
      <section class="inner-hero section">
        <div class="container reveal">
          <div class="eyebrow"><?= t('pages.roadmap.badge') ?></div>
          <h1><?= t('pages.roadmap.title') ?></h1>
          <p class="hero-lead"><?= t('pages.roadmap.subtitle') ?></p>
        </div>
      </section>
      <section class="section">
        <div class="container roadmap-list">
          <article class="roadmap-item reveal is-now"><?= icon('check') ?><span>Q2 2026</span>
            <h3><?= t('pages.roadmap.q2.title') ?></h3>
            <p><?= t('pages.roadmap.q2.body') ?></p>
          </article>
          <article class="roadmap-item reveal"><?= icon('clock') ?><span>Q3 2026</span>
            <h3><?= t('pages.roadmap.q3.title') ?></h3>
            <p><?= t('pages.roadmap.q3.body') ?></p>
          </article>
          <article class="roadmap-item reveal"><?= icon('rocket') ?><span>Q4 2026</span>
            <h3><?= t('pages.roadmap.q4.title') ?></h3>
            <p><?= t('pages.roadmap.q4.body') ?></p>
          </article>
          <article class="roadmap-item reveal"><?= icon('map') ?><span>2027</span>
            <h3><?= t('pages.roadmap.y2027.title') ?></h3>
            <p><?= t('pages.roadmap.y2027.body') ?></p>
          </article>
        </div>
      </section>
    <?php elseif ($page === 'changelog'): ?>
      <section class="inner-hero section">
        <div class="container reveal">
          <div class="eyebrow"><?= t('pages.changelog.badge') ?></div>
          <h1><?= t('pages.changelog.title') ?></h1>
          <p class="hero-lead"><?= t('pages.changelog.subtitle') ?></p>
        </div>
      </section>
      <section class="section">
        <div class="container changelog-list">
          <article class="changelog-entry reveal"><time>2026-05-13</time>
            <h3><?= t('pages.changelog.items.site.title') ?></h3>
            <p><?= t('pages.changelog.items.site.body') ?></p>
          </article>
          <article class="changelog-entry reveal"><time>2026-05-12</time>
            <h3><?= t('pages.changelog.items.dashboard.title') ?></h3>
            <p><?= t('pages.changelog.items.dashboard.body') ?></p>
          </article>
          <article class="changelog-entry reveal"><time>2026-05-11</time>
            <h3><?= t('pages.changelog.items.runtime.title') ?></h3>
            <p><?= t('pages.changelog.items.runtime.body') ?></p>
          </article>
          <article class="changelog-entry reveal"><time>2026-05-10</time>
            <h3><?= t('pages.changelog.items.editors.title') ?></h3>
            <p><?= t('pages.changelog.items.editors.body') ?></p>
          </article>
        </div>
      </section>
    <?php elseif ($page === 'about'): ?>
      <section class="about-manifesto section">
        <div class="container reveal">
          <div class="eyebrow"><?= t('pages.about.badge') ?></div>
          <h1><?= t('pages.about.title') ?></h1>
          <p class="about-lead"><?= t('pages.about.lead') ?></p>
          <p class="about-note"><?= t('pages.about.note') ?></p>
        </div>
      </section>
      <section class="section about-principles-section">
        <div class="container">
          <div class="section-heading reveal">
            <div class="eyebrow"><?= t('pages.about.manifestoBadge') ?></div>
            <h2><?= t('pages.about.manifestoTitle') ?></h2>
          </div>
          <div class="about-principles">
            <?php for ($i = 1; $i <= 10; $i++): ?>
              <article class="about-principle reveal">
                <span><?= str_pad((string) $i, 2, '0', STR_PAD_LEFT) ?></span>
                <h3><?= t('pages.about.principles.p' . $i . '.title') ?></h3>
                <p><?= t('pages.about.principles.p' . $i . '.body') ?></p>
              </article>
            <?php endfor; ?>
          </div>
        </div>
      </section>
      <section class="section about-story-section">
        <div class="container about-story reveal">
          <div class="eyebrow"><?= t('pages.about.storyBadge') ?></div>
          <div>
            <p><?= t('pages.about.story.one') ?></p>
            <p><?= t('pages.about.story.two') ?></p>
            <p><?= t('pages.about.story.three') ?></p>
            <p><?= t('pages.about.story.four') ?></p>
          </div>
          <small><?= t('pages.about.story.footer') ?></small>
        </div>
      </section>
    <?php elseif ($page === 'docs'): ?>
      <section class="inner-hero section">
        <div class="container reveal">
          <div class="eyebrow"><?= t('pages.docs.badge') ?></div>
          <h1><?= t('pages.docs.title') ?></h1>
          <p class="hero-lead"><?= t('pages.docs.subtitle') ?></p>
        </div>
      </section>
      <section class="section">
        <div class="container docs-page-grid">
          <aside class="docs-toc reveal">
            <a href="#runtime">Runtime</a>
            <a href="#workspace">Workspace</a>
            <a href="#boxes">boxLens / boxTracker</a>
            <a href="#storage">IndexedDB</a>
            <a href="#screens">Schermate</a>
            <a href="#api">API</a>
          </aside>
          <div class="docs-content reveal">
            <article id="runtime">
              <h2><?= t('pages.docs.runtime.title') ?></h2>
              <p><?= t('pages.docs.runtime.body') ?></p>
            </article>
            <article id="workspace">
              <h2><?= t('pages.docs.workspace.title') ?></h2>
              <p><?= t('pages.docs.workspace.body') ?></p>
              <pre><code>workspace.html?workspaceId=&lt;id-workspace&gt;</code></pre>
            </article>
            <article id="boxes">
              <h2><?= t('pages.docs.boxes.title') ?></h2>
              <p><?= t('pages.docs.boxes.body') ?></p>
            </article>
            <article id="storage">
              <h2><?= t('pages.docs.storage.title') ?></h2>
              <p><?= t('pages.docs.storage.body') ?></p>
              <pre><code>TrackersLens
tl_widgets
tl_pages
tl_connections
tl_settings</code></pre>
            </article>
            <article id="screens">
              <h2><?= t('pages.docs.screens.title') ?></h2>
              <p><?= t('pages.docs.screens.body') ?></p>
            </article>
            <article id="api">
              <h2><?= t('pages.docs.api.title') ?></h2>
              <p><?= t('pages.docs.api.body') ?></p><a class="btn btn-secondary" href="<?= $apiBaseUrl ?>/docs/api-contract"><?= t('developers.api') ?></a>
            </article>
          </div>
        </div>
      </section>
    <?php elseif ($page === 'privacy' || $page === 'terms'): ?>
      <section class="inner-hero section">
        <div class="container reveal">
          <div class="eyebrow"><?= t('pages.' . $page . '.badge') ?></div>
          <h1><?= t('pages.' . $page . '.title') ?></h1>
          <p class="hero-lead"><?= t('pages.' . $page . '.subtitle') ?></p>
        </div>
      </section>
      <section class="section">
        <div class="container legal-content reveal">
          <article>
            <h2><?= t('pages.' . $page . '.sections.s1.title') ?></h2>
            <p><?= t('pages.' . $page . '.sections.s1.body') ?></p>
          </article>
          <article>
            <h2><?= t('pages.' . $page . '.sections.s2.title') ?></h2>
            <p><?= t('pages.' . $page . '.sections.s2.body') ?></p>
          </article>
          <article>
            <h2><?= t('pages.' . $page . '.sections.s3.title') ?></h2>
            <p><?= t('pages.' . $page . '.sections.s3.body') ?></p>
          </article>
          <article>
            <h2><?= t('pages.' . $page . '.sections.s4.title') ?></h2>
            <p><?= t('pages.' . $page . '.sections.s4.body') ?></p>
          </article>
        </div>
      </section>
    <?php elseif ($page === 'blog'): ?>
      <section class="inner-hero section">
        <div class="container reveal">
          <div class="eyebrow"><?= t('pages.blog.badge') ?></div>
          <h1><?= t('pages.blog.title') ?></h1>
          <p class="hero-lead"><?= t('pages.blog.subtitle') ?></p>
        </div>
      </section>
      <section class="section">
        <div class="container blog-grid">
          <article class="feature-card reveal accent-gold"><span>2026-05-14</span>
            <h3><?= t('pages.blog.items.docs.title') ?></h3>
            <p><?= t('pages.blog.items.docs.body') ?></p><a class="btn btn-secondary" href="<?= page_url('docs') ?>"><?= t('footer.docs') ?></a>
          </article>
          <article class="feature-card reveal accent-cyan"><span>2026-05-13</span>
            <h3><?= t('pages.blog.items.api.title') ?></h3>
            <p><?= t('pages.blog.items.api.body') ?></p><a class="btn btn-secondary" href="<?= $apiBaseUrl ?>/docs/api-contract"><?= t('footer.api') ?></a>
          </article>
          <article class="feature-card reveal accent-purple"><span>2026-05-12</span>
            <h3><?= t('pages.blog.items.runtime.title') ?></h3>
            <p><?= t('pages.blog.items.runtime.body') ?></p><a class="btn btn-secondary" href="<?= page_url('roadmap') ?>"><?= t('footer.roadmap') ?></a>
          </article>
        </div>
      </section>
    <?php endif; ?>
  </main>

  <footer class="site-footer">
    <div class="container footer-grid">
      <div class="footer-brand">
        <a class="brand" href="<?= page_url('home') ?>#hero" aria-label="Trackers Lens home">
          <img class="brand-mark" src="/assets/icons/logo.svg" alt="" aria-hidden="true" width="42" height="42" loading="lazy" decoding="async">
          <span class="brand-name"><span>Trackers</span> <strong>Lens</strong></span>
        </a>
        <p><?= t('footer.brandText') ?></p>
        <div class="socials" aria-label="Social links">
          <a href="https://github.com/trackerslens" aria-label="GitHub" target="_blank" rel="noopener noreferrer"><?= icon('github') ?></a>
          <a href="https://x.com/trackerslens" aria-label="X Twitter" target="_blank" rel="noopener noreferrer"><?= icon('twitter') ?></a>
          <a href="https://discord.gg/trackerslens" aria-label="Discord" target="_blank" rel="noopener noreferrer"><?= icon('discord') ?></a>
          <a href="https://www.youtube.com/@trackerslens" aria-label="YouTube" target="_blank" rel="noopener noreferrer"><?= icon('youtube') ?></a>
        </div>
      </div>
      <div>
        <h3><?= t('footer.product') ?></h3>
        <a href="<?= page_url('features') ?>"><?= t('footer.features') ?></a>
        <a href="<?= page_url('pricing') ?>"><?= t('footer.pricing') ?></a>
        <a href="<?= page_url('roadmap') ?>"><?= t('footer.roadmap') ?></a>
        <a href="<?= page_url('changelog') ?>"><?= t('footer.changelog') ?></a>
      </div>
      <div>
        <h3><?= t('footer.resources') ?></h3>
        <a href="<?= page_url('docs') ?>"><?= t('footer.docs') ?></a>
        <a href="<?= $apiBaseUrl ?>/docs/api-contract"><?= t('footer.api') ?></a>
        <a href="<?= page_url('home') ?>#demo"><?= t('footer.examples') ?></a>
        <a href="<?= page_url('blog') ?>"><?= t('footer.blog') ?></a>
      </div>
      <div>
        <h3><?= t('footer.company') ?></h3>
        <a href="<?= page_url('about') ?>"><?= t('footer.about') ?></a>
        <button class="footer-link" type="button" data-contact-open><?= t('footer.contact') ?></button>
        <a href="<?= page_url('privacy') ?>"><?= t('footer.privacy') ?></a>
        <a href="<?= page_url('terms') ?>"><?= t('footer.terms') ?></a>
      </div>
      <div>
        <h3><?= t('footer.newsletter') ?></h3>
        <p><?= t('footer.newsletterText') ?></p>
        <form class="newsletter" aria-label="Newsletter signup" data-newsletter-form>
          <input type="email" name="email" placeholder="<?= t('footer.emailPlaceholder') ?>" aria-label="Email" required autocomplete="email">
          <button type="submit" aria-label="<?= t('footer.newsletterSubmit') ?>">→</button>
        </form>
        <p class="form-message" data-newsletter-message aria-live="polite"></p>
      </div>
    </div>
    <div class="container footer-bottom">
      <span><?= t('footer.copyright') ?></span>
      <span>trackerslens.com</span>
    </div>
  </footer>

  <div class="contact-modal" data-contact-modal aria-hidden="true" hidden>
    <div class="auth-backdrop" data-contact-close></div>
    <section class="auth-dialog contact-dialog" role="dialog" aria-modal="true" aria-labelledby="contact-title">
      <button class="auth-close" type="button" aria-label="<?= t('contact.close') ?>" data-contact-close>×</button>
      <div class="auth-kicker"><?= t('contact.kicker') ?></div>
      <h2 id="contact-title"><?= t('contact.title') ?></h2>
      <p><?= t('contact.body') ?></p>
      <form class="auth-form contact-form" data-contact-form>
        <label><span><?= t('auth.name') ?></span><input type="text" name="name" required autocomplete="name"></label>
        <label><span><?= t('auth.email') ?></span><input type="email" name="email" required autocomplete="email"></label>
        <label><span><?= t('contact.message') ?></span><textarea name="message" required rows="5"></textarea></label>
        <button class="btn btn-primary btn-large" type="submit"><?= icon('mail') ?><span><?= t('contact.submit') ?></span></button>
        <p class="form-message" data-contact-message aria-live="polite"></p>
      </form>
      <p class="launch-note"><?= t('contact.note') ?></p>
    </section>
  </div>

  <div class="launch-modal" data-launch-modal aria-hidden="true" hidden>
    <div class="auth-backdrop" data-launch-close></div>
    <section class="auth-dialog launch-dialog" role="dialog" aria-modal="true" aria-labelledby="launch-title">
      <button class="auth-close" type="button" aria-label="<?= t('launch.close') ?>" data-launch-close>×</button>
      <div class="auth-kicker"><?= t('launch.kicker') ?></div>
      <h2 id="launch-title"><?= t('launch.title') ?></h2>
      <p><?= t('launch.body') ?></p>
      <form class="auth-form launch-form" data-launch-form>
        <label>
          <span><?= t('auth.email') ?></span>
          <input type="email" name="email" placeholder="<?= t('footer.emailPlaceholder') ?>" required autocomplete="email">
        </label>
        <button class="btn btn-primary btn-large" type="submit"><?= icon('notify') ?><span><?= t('launch.submit') ?></span></button>
        <p class="form-message" data-launch-message aria-live="polite"></p>
      </form>
      <p class="launch-note"><?= t('launch.note') ?></p>
    </section>
  </div>

  <div class="auth-modal" data-login-modal aria-hidden="true" hidden>
    <div class="auth-backdrop" data-login-close></div>
    <section class="auth-dialog" role="dialog" aria-modal="true" aria-labelledby="auth-title">
      <button class="auth-close" type="button" aria-label="<?= t('auth.close') ?>" data-login-close>×</button>
      <div class="auth-kicker"><?= t('auth.kicker') ?></div>
      <h2 id="auth-title"><?= t('auth.loginTitle') ?></h2>
      <p><?= t('auth.loginBody') ?></p>
      <div class="auth-tabs" role="tablist" aria-label="<?= t('auth.tabsLabel') ?>">
        <button type="button" class="is-active" data-auth-mode="login"><?= t('auth.loginTab') ?></button>
        <button type="button" data-auth-mode="register"><?= t('auth.registerTab') ?></button>
      </div>
      <form class="auth-form" data-auth-form>
        <label data-register-only hidden>
          <span><?= t('auth.name') ?></span>
          <input type="text" name="name" autocomplete="name">
        </label>
        <label>
          <span><?= t('auth.email') ?></span>
          <input type="email" name="email" required autocomplete="email">
        </label>
        <label>
          <span><?= t('auth.password') ?></span>
          <input type="password" name="password" required autocomplete="current-password" minlength="8">
        </label>
        <label data-register-only hidden>
          <span><?= t('auth.passwordConfirmation') ?></span>
          <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8">
        </label>
        <label class="auth-check" data-login-only>
          <input type="checkbox" name="remember" value="1">
          <span><?= t('auth.remember') ?></span>
        </label>
        <button class="btn btn-primary btn-large" type="submit" data-auth-submit><?= icon('login') ?><span data-auth-submit-label><?= t('auth.loginSubmit') ?></span></button>
        <p class="form-message" data-auth-message aria-live="polite"></p>
      </form>
    </section>
  </div>
</body>

</html>
