# Liqahi (لقاحي)

Vaccine availability locator for Iraqi medical centers. Public users find the nearest center stocking a needed vaccine; center staff toggle availability; super admins manage everything.

**Stack:** Laravel 13, PHP 8.4, Filament v5, Livewire v4, Tailwind v4, PostgreSQL 16, Leaflet, Spatie Permission.

## Quick start (Docker)

```bash
cp .env.example .env
make up
make fresh        # migrate + seed (roles, super admin, 32 items, 15 Baghdad centers)
```

App runs on http://localhost:8000. Set `ADMIN_EMAIL` / `ADMIN_PASSWORD` in the environment before seeding; the super admin is created from those values (seeding aborts if `ADMIN_PASSWORD` is empty).

## Quick start (Herd / local PHP)

Postgres + Redis only via Docker; PHP served by Herd at `https://liqahi.test`.

```bash
cp .env.example .env
# Edit .env: DB_HOST=127.0.0.1, DB_PORT=5433
make dev-up
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate --seed
```

## Environment

| Var | Default | Notes |
|---|---|---|
| `APP_LOCALE` | `ar` | Arabic default; falls back to English. |
| `DB_*` | — | Compose preset: `liqahi/liqahi/liqahi` on `postgres:5432` (5433 from host). |
| `ADMIN_EMAIL` | `admin@liqahi.local` | Used by `SuperAdminSeeder`. |
| `ADMIN_PASSWORD` | — | Required by `SuperAdminSeeder`; set a strong value. |

## Panels & roles

- `/admin` — `super_admin` only. CRUD centers / items / users + stats.
- `/center` — `center_owner` and `staff`. Tenant-scoped to their `medical_center_id`.
  - Item availability toggle (both roles).
  - Staff CRUD (owner only).
- `/` — public Livewire search page.

Permissions seeded by `RolePermissionSeeder`:
`manage_centers`, `manage_users`, `manage_items`, `manage_center_staff`, `toggle_availability`.

## Seeders

`DatabaseSeeder` runs in order:

1. `RolePermissionSeeder` — Spatie roles + permissions.
2. `SuperAdminSeeder` — single super admin from `ADMIN_EMAIL`/`ADMIN_PASSWORD`.
3. `ItemSeeder` — 32 vaccines / supplies (idempotent on `name_ar`).
4. `MedicalCenterSeeder` — 15 real Baghdad hospitals with district-accurate coords; randomized availability.

Reset everything: `make fresh`.

Backfill missing pivot rows for one or all centers:
```bash
php artisan liqahi:link-items {center_id?}
```

The `MedicalCenterObserver` auto-attaches the full item catalog (with `is_available=false`) to any new center.

## Commands

```bash
make up        # docker compose up -d
make down
make sh        # shell into app container
make migrate
make fresh     # migrate:fresh --seed
make seed
make test      # pest, compact output
make pint      # format PHP
make dev-up    # postgres+redis only (for Herd workflows)
make dev-down
```

## Tests

Pest, runs against a Postgres `liqahi_test` database (Haversine SQL needs Postgres). First-time setup:

```bash
docker exec liqahi-postgres psql -U liqahi -d postgres -c "CREATE DATABASE liqahi_test;"
php artisan test --compact
```

Coverage: panel access (super_admin/center_owner/staff), tenancy isolation, availability-toggle audit fields, public search ordering/radius/filtering, geocoder cache + graceful 5xx.

## Theming

Filament's default Zinc/Amber palette. Public site uses Tailwind directly with matching `zinc`/`amber` classes. Dark mode persists to `localStorage` on the public site; per-user on Filament. Language toggle stores in session and `users.locale` when authenticated.

## Public endpoints

- `GET /` — search page (rate-limited 60/min/ip).
- `POST /locale/{ar|en}` — switch locale (rate-limited 60/min/ip).
- `GET /sitemap.xml`, `/robots.txt` — SEO basics. `/admin`, `/center`, `/livewire` disallowed.
- `GET /up` — health check.

## Deployment

Designed for [Laravel Cloud](https://cloud.laravel.com/) or any container host. Production image: `docker compose build app` produces a self-contained php-fpm image with assets compiled (`npm run build` runs in the build stage). Storage is mounted at `/var/www/html/storage`.

Don't forget `php artisan config:cache && php artisan route:cache && php artisan view:cache` post-deploy.
