# Landing page Trackers Lens

Data: 12 maggio 2026

## Stato

Landing page convertita a PHP server-side in `/Users/cmalleux/Sites/trackerslens-site`.

## File creati

- `index.php`
- `lang.php`
- `router.php`
- `.htaccess`
- `assets/css/style.css`
- `assets/js/main.js`
- `LAVORO_LANDING_TRACKERS_LENS.md`

## Cosa e stato implementato

- Struttura PHP/HTML nativa senza framework e senza build system.
- Navbar sticky con logo, link, language switcher, login e CTA.
- Menu hamburger responsive per mobile/tablet.
- Hero a due colonne su desktop con mockup dashboard costruito in HTML/CSS.
- Sezioni richieste: valore, privacy/local runtime, architettura, boxLens/boxTracker, sviluppatori, statistiche, prezzi, CTA finale e footer.
- Stile dark premium con colori nero/blu scuro, neon viola, verde, oro e cyan.
- Logo collegato da `assets/icons/logo.svg`.
- Font esterni rimossi dal percorso critico per migliorare PageSpeed.
- Icone convertite a SVG inline per evitare Google Fonts/Material Symbols e FontAwesome nel rendering iniziale.
- Performance/PageSpeed: cache lunga per asset statici in `.htaccess`, logo prioritizzato per LCP, progress bar ARIA corretta.
- Glassmorphism leggero, glow, griglia/puntini di background e animazioni CSS leggere.
- Multi-lingua server-side con `lang.php` per italiano, inglese e spagnolo.
- Cambio lingua tramite URL SEO-friendly `/it/`, `/en/`, `/es/`.
- Fallback italiano, meta title/description/OpenGraph generati lato server.
- Tag canonical e `hreflang` per SEO internazionale.
- SEO completo: robots, sitemap XML, manifest, OpenGraph, Twitter Cards, JSON-LD e immagine social preview 1200x630.
- Stato prodotto aggiunto: messaggio "in sviluppo / lancio in arrivo" con progress bar a tema e CTA "avvisami al lancio".
- Smooth scrolling e reveal animation on scroll.
- SEO base, OpenGraph tags e favicon placeholder inline.

## Note tecniche

- Il sito ora richiede PHP sul server.
- Su hosting Apache, `.htaccess` abilita le route `/it/`, `/en/`, `/es/`.
- In locale, `router.php` permette di testare le stesse route con `php -S`.
- Non e presente un vero backend per newsletter, login, download o demo: i link sono placeholder.
- La dashboard nel hero e un mockup CSS, non un'immagine raster.

## Prossimi passi consigliati

- Collegare CTA download e login alle destinazioni reali.
- Sostituire `og:image` con un asset reale quando disponibile.
- Aggiungere altre lingue in `lang.php` se necessario.
- Integrare analytics privacy-friendly se necessario.
