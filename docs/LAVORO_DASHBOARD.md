# TrackerLens Dashboard - Stato lavori

Questo documento serve come traccia operativa per capire cosa e stato preparato prima della programmazione funzionale della dashboard.

## Base progetto

- Creato progetto dashboard con Vite.
- Configurato ingresso principale in `public/index.html`.
- Collegato bootstrap JavaScript in `public/src/main.js`.
- Collegati pacchetti `@cmswift/core` e `@cmswift/ui`.
- Aggiunta gestione errori di boot per mostrare problemi iniziali dentro `#app`.

## Struttura UI iniziale

- Aggiunto layout applicativo con `AppShell`.
- Aggiunto header con nome prodotto `TrackerLens`.
- Aggiunto drawer laterale con navigazione principale.
- Aggiunte rotte base:
  - `/` dashboard
  - `/projects` progetti
  - `/settings` impostazioni
- Aggiunta pagina 404 per percorsi non presenti.

## Multilingua

- Aggiunto modulo `public/src/i18n.js`.
- Centralizzate le stringhe UI in chiavi di traduzione.
- Lingue predisposte:
  - Italiano
  - Inglese
  - Francese
  - Spagnolo
  - Tedesco
- Aggiunto selettore lingua nel drawer.
- Persistenza lingua in `localStorage` con chiave `trackerlens.locale`.
- Aggiornamento attributo `lang` del documento in base alla lingua scelta.

## Stile

- Aggiunto CSS applicativo in `public/src/styles/app.css`.
- Definito stile base per brand drawer, navigazione, selettore lingua e messaggi di errore boot.

## Verifica

- Eseguita build con `npm run build`.
- Build completata correttamente.

## Prossimo passo

Prima di aggiungere logica applicativa reale, ogni nuova schermata deve usare le chiavi in `i18n.js` invece di testi scritti direttamente nei componenti. Questo mantiene la dashboard pronta per il multilingua fin dall'inizio.
