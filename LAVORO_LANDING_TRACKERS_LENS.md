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
- Link e CTA attivati: navigazione interna, risorse, footer, social, contatti e CTA launch.
- Popup login/register aggiunto con stile coerente alla landing.
- Popup auth collegato a `https://api.trackerslens.com`: `GET /sanctum/csrf-cookie`, `POST /api/login`, `POST /api/register`.
- Redirect post-login verso `https://app.trackerslens.com`.
- Newsletter/launch signup attivo lato pagina con salvataggio locale e feedback utente, in attesa di endpoint API dedicato.
- Pagine prodotto aggiunte: `/it/features/`, `/it/pricing/`, `/it/roadmap/`, `/it/changelog/`.
- `.htaccess` aggiornato per route SEO delle pagine prodotto.
- Sitemap aggiornata con pagine prodotto.
- Smooth scrolling e reveal animation on scroll.
- SEO base, OpenGraph tags e favicon placeholder inline.

## Note tecniche

- Il sito ora richiede PHP sul server.
- Su hosting Apache, `.htaccess` abilita le route `/it/`, `/en/`, `/es/`.
- In locale, `router.php` permette di testare le stesse route con `php -S`.
- Non e presente un endpoint backend newsletter/waitlist: al momento la landing salva l'email localmente.
- Blog, privacy e termini non esistono ancora come pagine standalone: i link usano ancore interne o mailto per evitare 404.
- La dashboard nel hero e un mockup CSS, non un'immagine raster.
- La documentazione API dell'integrazione e tracciata in `/Users/cmalleux/Sites/trackersLens-api/docs/landing-integration.md`.

## Prossimi passi consigliati

- Creare endpoint API newsletter/waitlist persistente.
- Creare pagine standalone per roadmap, changelog, blog, privacy e termini quando i contenuti saranno definitivi.
- Sostituire `og:image` con un asset reale quando disponibile.
- Aggiungere altre lingue in `lang.php` se necessario.
- Integrare analytics privacy-friendly se necessario.
