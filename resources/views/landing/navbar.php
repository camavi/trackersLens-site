<?php
/** Shared public site navigation. Values are supplied by the page that includes it. */
?>
<header class="site-header" <?= empty($marketplaceNav) ? 'data-header' : '' ?>>
  <nav class="navbar container" aria-label="Primary navigation">
    <a class="brand" href="<?= htmlspecialchars($navHomeUrl, ENT_QUOTES, 'UTF-8') ?>#hero" aria-label="Trackers Lens home">
      <img class="brand-mark" src="/assets/icons/logo.svg" alt="" aria-hidden="true" width="42" height="42" decoding="async">
      <span class="brand-name"><span>Trackers</span> <strong>Lens</strong></span>
      <span class="brand-arrow" aria-hidden="true">›</span>
    </a>

    <button class="nav-toggle" type="button" aria-label="Apri menu" aria-expanded="false" aria-controls="site-menu" data-menu-toggle>
      <span></span><span></span><span></span>
    </button>

    <div class="nav-panel" id="site-menu" data-menu>
      <ul class="nav-links">
        <li><a href="<?= htmlspecialchars($navFeaturesUrl, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($navLabels['features'], ENT_QUOTES, 'UTF-8') ?></a></li>
        <li><a href="<?= htmlspecialchars($navHomeUrl, ENT_QUOTES, 'UTF-8') ?>#why"><?= htmlspecialchars($navLabels['why'], ENT_QUOTES, 'UTF-8') ?></a></li>
        <li><a href="/marketplace" <?= !empty($marketplaceNav) ? 'aria-current="page"' : '' ?>><?= htmlspecialchars($navLabels['marketplace'], ENT_QUOTES, 'UTF-8') ?></a></li>
        <li><a href="<?= htmlspecialchars($navPricingUrl, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($navLabels['pricing'], ENT_QUOTES, 'UTF-8') ?></a></li>
        <li><a href="<?= htmlspecialchars($navDocsUrl, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($navLabels['docs'], ENT_QUOTES, 'UTF-8') ?></a></li>
      </ul>

      <div class="nav-actions">
        <label class="language-select" aria-label="<?= htmlspecialchars($navLabels['language'], ENT_QUOTES, 'UTF-8') ?>">
          <span class="sr-only"><?= htmlspecialchars($navLabels['language'], ENT_QUOTES, 'UTF-8') ?></span>
          <select <?= empty($marketplaceNav) ? 'data-language-switcher' : 'data-marketplace-language' ?> aria-label="Language switcher">
            <?php foreach ($navLanguages as $code => $option): ?>
              <option value="<?= htmlspecialchars($option['url'], ENT_QUOTES, 'UTF-8') ?>" <?= $navLocale === $code ? 'selected' : '' ?>><?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <?php if (empty($marketplaceNav)): ?>
          <button class="btn btn-ghost" type="button" data-login-open><span class="tl-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5M15 12H3M14 4h4a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-4"/></svg></span><?= htmlspecialchars($navLabels['login'], ENT_QUOTES, 'UTF-8') ?></button>
          <button class="btn btn-primary" type="button" data-launch-open><span class="tl-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg></span><?= htmlspecialchars($navLabels['download'], ENT_QUOTES, 'UTF-8') ?></button>
        <?php else: ?>
          <a class="btn btn-ghost" href="/app/marketplace?from=public" data-marketplace-login><span class="tl-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5M15 12H3M14 4h4a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-4"/></svg></span><?= htmlspecialchars($navLabels['login'], ENT_QUOTES, 'UTF-8') ?></a>
          <a class="btn btn-primary" href="<?= htmlspecialchars($navHomeUrl, ENT_QUOTES, 'UTF-8') ?>#hero" data-focus-newsletter><span class="tl-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg></span><?= htmlspecialchars($navLabels['download'], ENT_QUOTES, 'UTF-8') ?></a>
        <?php endif; ?>
      </div>
    </div>
  </nav>
</header>
