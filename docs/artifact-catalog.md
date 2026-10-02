# Desktop artifact catalog

Authenticated Sanctum API for Custom Node, Flow Map and Workspace releases, consumed by the desktop's Main-owned Profile session and the signed-in web dashboard Marketplace browser.

- `GET /api/catalog`: `kind=node|flowmap|workspace`, optional `query`, `mine`, `page`; returns `items`, `page`, `hasMore`, `total` (20 per page).
- `POST /api/catalog`: optional existing `artifactId`, `kind`, `version`, `title`, `description`, `license`, `visibility`, `bundleJson`. Creates an immutable release and returns identity and SHA-256. Only an artifact owner can publish a new version; existing versions return 409.
- `GET /api/catalog/{artifactId}/versions/{version}`: returns exact `bundleJson` bytes as a JSON string and `item` metadata with SHA-256. Private releases are owner-only. Unlisted releases are accessible by known ID/version to authenticated accounts but omitted from others' search. Public releases are searchable.

For Flow Map and Workspace, `bundleJson` follows `tl-catalog-bundle/v1` (root identity and allow-listed definition records). For Custom Nodes it follows `tl-custom-node-marketplace/v1` and carries the manifest, base64 `.tl-node.zip` and archive SHA-256; the server checks ZIP signature, release version and archive hash. Desktop review/install remains required. The service stores each immutable snapshot as long text. It does not execute, sign, mark verified, activate or install nodes. The web dashboard Marketplace is a signed-in, free-catalog browser with typed search and checksummed release downloads; downloaded graph/workspace catalog JSON is a portable snapshot, while the desktop online import remains the guided local install path. This is free catalog distribution only: no checkout, payment entitlement, refund, publisher settlement or automatic-update service is included.

Apply `database/migrations/2026_09_27_000000_create_catalog_releases_table.php` on deployment. Tests use disposable databases: `php artisan test --compact`. Desktop integration test: `env -u ELECTRON_RUN_AS_NODE node_modules/.bin/electron test/catalog-electron.cjs` in trackerLens.
