# Landing integration log

Date: 2026-05-14

## Public URLs

- Website: `https://trackerslens.com`
- API: `https://trackerslens.com`
- Dashboard: `https://trackerslens.com/app`

## Implemented on the landing

- Login button opens an in-page modal matching the landing style.
- Login modal calls `GET /sanctum/csrf-cookie`, then `POST /api/login`.
- Register tab calls `POST /api/register`.
- Successful auth redirects to `https://trackerslens.com/app`.
- CTA buttons scroll to the launch/newsletter area and focus the email input.
- Newsletter and launch modal call `POST /api/launch-subscriptions` and keep localStorage only as a local fallback/history marker.
- Contact modal calls `POST /api/contact-messages`; the backend stores the request and sends the internal notification email when configured.
- Footer links no longer use `#` placeholders. Existing sections use anchors; API reference uses `https://trackerslens.com/docs/api-contract`.
- Social links point to public Trackers Lens profiles/placeholders.

## API routes used by the landing

- `GET /sanctum/csrf-cookie`
- `POST /api/login`
- `POST /api/register`
- `POST /api/launch-subscriptions`
- `POST /api/contact-messages`
- `GET /docs/api-contract`
- `GET /docs/api-contract?format=md`
- `GET /docs/landing-integration`
- `GET /docs/landing-integration?format=md`

## Required production environment

For Sanctum cookie auth across the public domains, production `.env` should include values equivalent to:

```dotenv
APP_URL=https://trackerslens.com
FRONTEND_URL=https://trackerslens.com
CORS_ALLOWED_ORIGINS=https://trackerslens.com,https://www.trackerslens.com
SESSION_DOMAIN=.trackerslens.com
SANCTUM_STATEFUL_DOMAINS=trackerslens.com,www.trackerslens.com
MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_HOST=smtp.mail.ovh.net
MAIL_PORT=465
MAIL_USERNAME=hello@trackerslens.com
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=hello@trackerslens.com
MAIL_FROM_NAME="Trackers Lens API"
MAIL_INTERNAL_TO=hello@trackerslens.com
```

For OVH MX Plan, Laravel/Symfony must use `MAIL_SCHEME=smtps` for implicit SSL/TLS on port `465`. The legacy `MAIL_ENCRYPTION=ssl` variable is not read by this app's `config/mail.php`.

If the OVH shared hosting cluster refuses outbound SMTP connections, use the local sendmail transport instead:

```dotenv
MAIL_MAILER=sendmail
MAIL_SENDMAIL_PATH="/usr/sbin/sendmail -t -i"
```

## Pending API work

- Run the production migrations once the target database is confirmed: `php artisan migrate --force`.
- Add captcha/honeypot protection if public form spam becomes a real issue.
- Decide final public URLs for blog, roadmap, changelog, privacy policy and terms pages if they should become standalone pages instead of landing anchors.
