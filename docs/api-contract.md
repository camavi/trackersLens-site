# API contract

Base URL in development: `http://127.0.0.1:8000`
Production base URL: `https://trackerslens.com`

All application endpoints return JSON.

Documentation endpoints return HTML by default. Add `?format=md` or use the `.md` suffix for raw Markdown.

## Auth

### `GET /sanctum/csrf-cookie`

Initializes CSRF cookie for SPA requests.

### `POST /api/register`

Request:

```json
{
  "name": "Thomas Lane",
  "email": "user@example.com",
  "password": "secret-password",
  "password_confirmation": "secret-password"
}
```

Response: `201 Created` with the authenticated user payload.

### `POST /api/login`

Request:

```json
{
  "email": "user@example.com",
  "password": "secret",
  "remember": true
}
```

Response: `204 No Content` on success.

### `POST /api/logout`

Response: `204 No Content` on success.

### `GET /api/user`

Response:

```json
{
  "id": 1,
  "name": "Thomas Lane",
  "email": "user@example.com",
  "created_at": "2026-09-25T10:00:00.000000Z",
  "email_verified_at": null
}
```

### `PATCH /api/user`

Authenticated profile update. Required fields: `name`, `email`, `current_password`. Email must be unique. A changed email clears the previous verification timestamp. Returns the current user payload, without credentials or inferred subscription entitlements. Rate limit: 10 requests/minute.

### `PUT /api/user/password`

Authenticated password change. Required fields: `current_password`, `password`, `password_confirmation`. The new password must differ from the current password and contain at least 8 characters. Returns `204 No Content`; rotates the remember token and the current session ID. This operation does not claim to revoke every active session. Rate limit: 10 requests/minute.

Account mutations return `401` for unauthenticated callers and `422` with field errors for invalid input. Cookie-authenticated clients must initialize CSRF and send `X-XSRF-TOKEN` as for login. Desktop clients use a Main-owned Electron session; the website continues using same-origin browser cookies.

## Landing

Public landing endpoints are rate-limited and return JSON.

### `POST /api/launch-subscriptions`

Stores an email for launch notifications. If the email already exists, the row is updated.

Request:

```json
{
  "email": "user@example.com",
  "source": "launch_modal",
  "locale": "en"
}
```

Response: `201 Created` for a new subscription, `200 OK` for an existing email.

### `POST /api/contact-messages`

Stores a contact request and sends an internal notification email when `MAIL_INTERNAL_TO` is configured.

Request:

```json
{
  "name": "Thomas Lane",
  "email": "user@example.com",
  "message": "I want early access for Trackers Lens.",
  "source": "contact_modal",
  "locale": "en"
}
```

Response: `201 Created` on success.

## Public documentation

### `GET /docs/api-contract`

Returns this API contract as a styled HTML page.

Raw Markdown: `GET /docs/api-contract?format=md` or `GET /docs/api-contract.md`.

### `GET /docs/landing-integration`

Returns the landing/API integration log as a styled HTML page.

Raw Markdown: `GET /docs/landing-integration?format=md` or `GET /docs/landing-integration.md`.

### `GET /docs/laravel-backend-plan`

Returns the backend implementation plan as a styled HTML page.

Raw Markdown: `GET /docs/laravel-backend-plan?format=md` or `GET /docs/laravel-backend-plan.md`.

## Dashboard

### `GET /api/dashboard/summary`

Response:

```json
{
  "kpis": [
    {
      "label": "Total Boxes",
      "value": 24,
      "delta": 18,
      "trend": "up"
    }
  ],
  "box_segments": [
    {
      "label": "Published",
      "value": 12
    }
  ]
}
```

### `GET /api/dashboard/activity`

Response:

```json
{
  "items": [
    {
      "type": "box_published",
      "title": "Box published",
      "detail": "Crypto Tracker",
      "created_at": "2026-05-13T11:30:00Z"
    }
  ]
}
```

### `GET /api/dashboard/system-status`

Response:

```json
{
  "services": [
    {
      "name": "API Service",
      "status": "operational"
    }
  ]
}
```
