# INFO_AI - Trackers Lens Dashboard

## Fatto

- Letto `CMSwift.md` e usato CMSwift/CMSwift UI come base obbligatoria.
- Riorganizzata l'app in:
  - `public/src/layouts/`
  - `public/src/pages/`
  - `public/src/components/`
  - `public/src/services/`
- Sostituita la dashboard placeholder con un command center dark per `app.trackerslens.com`.
- Aggiunti topbar sticky, search globale CMSwift, sidebar fissa, avatar online, notifiche e help.
- Aggiunte sezioni:
  - KPI cards
  - Recent Activity
  - Account Overview con donut chart
  - Resource Usage
  - Top Performing Boxes
  - Quick Actions
  - System Status
- Usati componenti CMSwift UI: `AppShell`, `Header`, `Drawer`, `Page`, `Container`, `Grid`, `GridCol`, `Card`, `Btn`, `Search`, `Avatar`, `Badge`, `Progress`, `Tooltip`, `Spinner`, `Separator`, `Row`, `Kpi`, `Icon`.
- Copiato lo sprite Tabler in `public/cmswift-fe/img/svg/tabler-icons-sprite.svg` per renderizzare icone Tabler tramite `CMSwift.ui.Icon`.
- Aggiornato `public/index.html` con naming Trackers Lens.
- Aggiunti favicon/browser icons per SVG, PNG 48 e Apple touch icon.
- Aggiornata la build Vite per copiare in `dist` gli asset statici necessari a produzione:
  - `src/icons`
  - `cmswift-fe`
- Il logo topbar ora e importato via Vite (`logoUrl`) e non dipende piu dal path dev `/src/icons/logo.svg`.
- Corretto `Header`: la search globale ora usa il prop CMSwift `body` invece di `center`, cosi viene renderizzata anche in produzione.
- Aumentato lo spacing visivo tra KPI/cards dashboard: oltre al gap, aggiunto padding laterale alle colonne grid per separare fisicamente i bordi delle card.
- Refactor componenti dashboard/layout: usato `_.*` come API principale al posto di `CMSwift.ui.*`, coerente con il runtime CMSwift del progetto.
- Eseguita `npm run build` con successo.
- Avviato server Vite locale su `http://127.0.0.1:5173/`.
- Verificato rendering headless con Chrome: la dashboard monta senza boot error e lo sprite Tabler risponde `200`.

## Da fare

- Collegare dati reali da API/account cloud.
- Implementare schermate operative complete per Cloud Assets, Marketplace, My Boxes, Publish Box, Profile, API Keys, Statistics e Settings.
- Collegare login/sessione utente reale.
- Aggiungere test browser/screenshot quando il flusso UI sara definitivo.
