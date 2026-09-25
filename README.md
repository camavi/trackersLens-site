# Trackers Lens — sito unificato

Un solo progetto Laravel per sito pubblico, dashboard e API sullo stesso dominio.

- `/` → sito multilingua (`/it/`, `/en/`, `/es/`).
- `/app` e `/app/*` → dashboard CMSwift esistente.
- `/api/*` → API Laravel/Sanctum esistenti.
- `/sanctum/csrf-cookie` → cookie CSRF per login e registrazione.
- `/docs/api-contract` → documentazione API.

## Avvio locale

```sh
composer install
npm ci
# Solo per una nuova installazione: copiare .env.example in .env,
# generare APP_KEY con php artisan key:generate e configurare il database.
php artisan migrate
npm run dev
```

Aprire l’indirizzo stampato da Laravel (normalmente http://127.0.0.1:8000).
Se la porta è occupata, il login supporta anche la porta alternativa scelta dal server. `npm run dev` compila sito e dashboard,
poi avvia Laravel sullo stesso indirizzo. Dopo modifiche agli asset,
eseguire nuovamente `npm run build`; non è configurato hot reload.
`composer dev` richiama lo stesso avvio. `npm test` esegue i test Laravel.

## Organizzazione

- `resources/views/landing/`: sito PHP e traduzioni esistenti.
- `public/assets/`: CSS, JavaScript, immagini e font del sito.
- `resources/dashboard/`: sorgenti dashboard e asset CMSwift.
- `public/build/dashboard/`: dashboard generata da Vite, esclusa da Git.
- `app/`, `routes/`, `database/`, `config/`: backend Laravel.
- `tools/`: script del sito, incluso il generatore dell'immagine social.

La dashboard importata usa `@cmswift/core` e `@cmswift/ui`; il passaggio
al pacchetto JSswift corrente non fa parte di questa unificazione.
Dati dimostrativi e schermate placeholder della dashboard sono conservati.
Il login del sito usa le API locali e apre `/app` dopo l'autenticazione.
La dashboard resta la stessa shell pubblica; le API private richiedono Sanctum.

## Deploy

Impostare la document root su `public/`, mai sulla radice del repository.
Eseguire `composer install --no-dev --optimize-autoloader`, `npm ci` e
`npm run build`; configurare `.env` per produzione e il database prima delle migrazioni.
Impostare `APP_ENV=production`, `APP_DEBUG=false`,
`APP_URL=https://trackerslens.com`, `FRONTEND_URL=https://trackerslens.com`,
`SANCTUM_STATEFUL_DOMAINS=trackerslens.com,www.trackerslens.com` e
`SESSION_SECURE_COOKIE=true`. Usare `SESSION_DOMAIN=null` per cookie host-only.
Conservare la chiave APP_KEY esistente per i dati cifrati.

La migrazione locale ha copiato configurazione privata, storage e database SQLite
in questo progetto (esclusi da Git); i repository originali restano intatti.
Non sono stati modificati hosting, DNS o dati di produzione. Gli eventuali redirect
dai precedenti sottodomini API/app vanno configurati al momento del deploy;
per i client API preservare metodo e corpo della richiesta.
