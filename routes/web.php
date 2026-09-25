<?php

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use League\CommonMark\CommonMarkConverter;

Route::get('/', fn () => redirect('/it/'));
Route::get('/{locale}/{page?}', fn () => view('landing.index'))
    ->where('locale', 'it|en|es')
    ->where('page', 'features|pricing|roadmap|changelog|docs|privacy|terms|blog|about')
    ->withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class]);
Route::get('/app/{path?}', function () {
    abort_unless(is_file(public_path('build/dashboard/index.html')), 503, 'Run npm run build first.');
    return response()->file(public_path('build/dashboard/index.html'), ['Cache-Control' => 'no-cache']);
})->where('path', '.*');

Route::get('/docs/{document}', function (string $document) {
    $rawMarkdown = false;
    if (str_ends_with($document, '.md')) {
        $rawMarkdown = true;
        $document = substr($document, 0, -3);
    }

    $allowedDocuments = [
        'api-contract' => base_path('docs/api-contract.md'),
        'landing-integration' => base_path('docs/landing-integration.md'),
        'laravel-backend-plan' => base_path('docs/laravel-backend-plan.md'),
    ];
    $titles = [
        'api-contract' => 'API Contract',
        'landing-integration' => 'Landing Integration',
        'laravel-backend-plan' => 'Backend Plan',
    ];

    abort_unless(array_key_exists($document, $allowedDocuments), 404);
    abort_unless(is_file($allowedDocuments[$document]), 404);

    $markdown = file_get_contents($allowedDocuments[$document]);

    if ($rawMarkdown || request()->query('format') === 'md') {
        return response($markdown, 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
        ]);
    }

    $converter = new CommonMarkConverter([
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
    ]);
    $content = $converter->convert($markdown)->getContent();
    $title = $titles[$document];
    $rawUrl = url()->current().'?format=md';
    $homeUrl = url('/');
    $nav = collect($titles)
        ->map(fn (string $label, string $key): string => sprintf(
            '<a class="%s" href="%s">%s</a>',
            $key === $document ? 'active' : '',
            e(url('/docs/'.$key)),
            e($label),
        ))
        ->implode('');

    $html = <<<HTML
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, follow">
  <title>{$title} - Trackers Lens API</title>
  <style>
    :root {
      --bg: #050712;
      --panel: rgba(13, 18, 34, .82);
      --line: rgba(160, 177, 255, .18);
      --line-strong: rgba(255, 178, 26, .45);
      --text: #eef2ff;
      --muted: #a7b0c8;
      --gold: #ffb21a;
      --purple: #9b5cff;
      --code: #070a13;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      min-width: 320px;
      background:
        radial-gradient(circle at 18% 10%, rgba(155, 92, 255, .18), transparent 28%),
        linear-gradient(180deg, var(--bg), #02040a);
      color: var(--text);
      font: 16px/1.65 ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }
    body::before {
      position: fixed;
      inset: 0;
      z-index: -1;
      content: "";
      background-image: radial-gradient(circle, rgba(163, 141, 255, .48) .8px, transparent 1px);
      background-size: 72px 56px;
      opacity: .42;
    }
    a { color: inherit; }
    .shell { width: min(100% - 32px, 1120px); margin: 0 auto; padding: 34px 0 64px; }
    .topbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 24px;
      padding: 16px;
      border: 1px solid var(--line);
      border-radius: 8px;
      background: rgba(5, 8, 18, .76);
      backdrop-filter: blur(16px);
    }
    .brand { font-weight: 900; text-transform: uppercase; text-decoration: none; }
    .brand strong { color: var(--gold); }
    .actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .actions a, .docs-nav a {
      display: inline-flex;
      min-height: 38px;
      align-items: center;
      border: 1px solid var(--line);
      border-radius: 8px;
      padding: 0 12px;
      color: var(--muted);
      text-decoration: none;
      font-weight: 800;
    }
    .actions a:hover, .docs-nav a:hover, .docs-nav a.active {
      color: var(--text);
      border-color: var(--line-strong);
    }
    .docs-layout { display: grid; grid-template-columns: 240px minmax(0, 1fr); gap: 20px; }
    .docs-nav {
      position: sticky;
      top: 16px;
      align-self: start;
      display: grid;
      gap: 8px;
      padding: 14px;
      border: 1px solid var(--line);
      border-radius: 8px;
      background: var(--panel);
    }
    main {
      min-width: 0;
      padding: 30px;
      border: 1px solid var(--line);
      border-radius: 8px;
      background: var(--panel);
      box-shadow: 0 24px 80px rgba(0, 0, 0, .28);
    }
    h1, h2, h3 { line-height: 1.15; margin: 1.35em 0 .5em; }
    h1 { margin-top: 0; font-size: clamp(2rem, 6vw, 3.3rem); }
    h2 { color: var(--gold); }
    p, li { color: #d9e0f7; }
    code {
      border: 1px solid rgba(160, 177, 255, .14);
      border-radius: 6px;
      padding: .1em .35em;
      background: var(--code);
      color: #ffd84a;
      font-family: "SFMono-Regular", Consolas, monospace;
      font-size: .92em;
    }
    pre {
      overflow: auto;
      padding: 18px;
      border: 1px solid var(--line);
      border-radius: 8px;
      background: var(--code);
    }
    pre code { border: 0; padding: 0; background: transparent; color: #eef2ff; }
    @media (max-width: 820px) {
      .topbar, .docs-layout { display: block; }
      .actions, .docs-nav { margin-top: 14px; }
      main { padding: 22px; }
    }
  </style>
</head>
<body>
  <div class="shell">
    <header class="topbar">
      <a class="brand" href="{$homeUrl}">Trackers <strong>Lens</strong> API</a>
      <div class="actions">
        <a href="/">Website</a>
        <a href="/app">Dashboard</a>
        <a href="{$rawUrl}">Raw Markdown</a>
      </div>
    </header>
    <div class="docs-layout">
      <nav class="docs-nav" aria-label="API documentation">
        {$nav}
      </nav>
      <main>
        {$content}
      </main>
    </div>
  </div>
</body>
</html>
HTML;

    return response($html, 200, [
        'Content-Type' => 'text/html; charset=UTF-8',
    ]);
})->withoutMiddleware([
    StartSession::class,
    ShareErrorsFromSession::class,
    PreventRequestForgery::class,
]);

Route::get('/docs/{document}.md', function (string $document) {
    $allowedDocuments = [
        'api-contract' => base_path('docs/api-contract.md'),
        'landing-integration' => base_path('docs/landing-integration.md'),
        'laravel-backend-plan' => base_path('docs/laravel-backend-plan.md'),
    ];

    abort_unless(array_key_exists($document, $allowedDocuments), 404);
    abort_unless(is_file($allowedDocuments[$document]), 404);

    return response(file_get_contents($allowedDocuments[$document]), 200, [
        'Content-Type' => 'text/markdown; charset=UTF-8',
    ]);
})->withoutMiddleware([
    StartSession::class,
    ShareErrorsFromSession::class,
    PreventRequestForgery::class,
]);
