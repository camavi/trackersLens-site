# Laravel backend plan

## Stack

- Laravel 13.
- Laravel Sanctum for SPA authentication with cookie sessions.
- Database through Eloquent migrations.
- API responses in JSON only.

## First modules

1. Auth
   - `POST /api/register`
   - `POST /api/login`
   - `POST /api/logout`
   - `GET /api/user`
   - `GET /sanctum/csrf-cookie`

2. Dashboard
   - `GET /api/dashboard/summary`
   - `GET /api/dashboard/activity`
   - `GET /api/dashboard/system-status`

3. Account
   - `GET /api/profile`
   - `PATCH /api/profile`
   - `GET /api/subscription`

4. Box management
   - `GET /api/boxes`
   - `POST /api/boxes`
   - `GET /api/boxes/{box}`
   - `PATCH /api/boxes/{box}`
   - `DELETE /api/boxes/{box}`

5. API keys
   - `GET /api/api-keys`
   - `POST /api/api-keys`
   - `DELETE /api/api-keys/{key}`

## Sanctum SPA settings

Local development values expected in Laravel `.env`:

```dotenv
APP_URL=http://127.0.0.1:8000
FRONTEND_URL=http://127.0.0.1:8000
SESSION_DOMAIN=127.0.0.1
SANCTUM_STATEFUL_DOMAINS=127.0.0.1:8000,localhost:8000
```

Frontend requests must call `/sanctum/csrf-cookie` before login or other state-changing requests that require CSRF protection.
