# Backend Setup
mysql -u root -h 127.0.0.1 -P 3306 -e "CREATE DATABASE IF NOT EXISTS icchepuran CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

This is a Laravel 13 + Filament v5 backend (API + admin CMS) for the Ichhe
Puran website rebuild. This doc is the setup guide for a fresh clone, plus a
troubleshooting reference for the exact problems we already hit getting this
running on Windows — read the troubleshooting section before assuming a new
error is something novel, it's very likely the same root cause.

## 1. Prerequisites

- **PHP 8.3 or newer**, with these extensions enabled: `intl`, `pdo_mysql`,
  `pdo_sqlite`, `mbstring`, `openssl`, `curl`, `fileinfo`, `gd`, `zip`,
  `sqlite3`, `exif`. Laravel 13 itself hard-requires PHP ^8.3 (not just in this
  repo's `composer.json` — `laravel/framework`, `pint`, `pail`, `phpunit`, and
  `pao` all require it too), and Filament requires `intl`.
- **Composer 2.x**.
- **MySQL** (8.x recommended), running and reachable.
- **Git**.
- Node/npm is **not required**. `package.json` is Laravel's unused default
  Vite scaffold — Filament ships its own prebuilt CSS/JS.

### Recommended local environment: Laragon

We used [Laragon](https://laragon.org/) (the full version, not "Laragon
Lite"/WAMP-lite) — it bundles PHP, MySQL, and Apache together and is easy to
version-switch. If you already have XAMPP or another stack, see the
troubleshooting section below before switching — the failure modes are known.

## 2. Clone and install

```bash
git clone <this-repo-url> backend
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

## 3. Set up the database (MySQL)

This project uses **MySQL**, not SQLite — `.env.example` already has the
right connection settings, you just need the database to exist.

1. Start MySQL (in Laragon: open the app, click **Start All**, or toggle
   MySQL on individually).
2. Create the database. Using the `mysql` CLI (adjust the path to wherever
   your MySQL binary lives, e.g. `C:\laragon\bin\mysql\mysql-<version>\bin\mysql.exe`
   on Laragon):
   ```bash
   mysql -u root -h 127.0.0.1 -P 3306 -e "CREATE DATABASE IF NOT EXISTS icchepuran CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```
   Or use a GUI tool (phpMyAdmin, TablePlus, etc.) to create a database named
   `icchepuran`.
3. Confirm `.env` has (this is already the default in `.env.example`):
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=icchepuran
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Laragon's default MySQL root user has **no password**. If your MySQL setup
   uses a different user/password, update these accordingly.
4. Run migrations and seed starting content:
   ```bash
   php artisan migrate
   php artisan db:seed --class=HomePageSeeder
   ```
5. Create your admin login:
   ```bash
   php artisan tinker --execute="\App\Models\User::create(['name'=>'Admin','email'=>'you@example.com','password'=>bcrypt('yourpassword')]);"
   ```
   (`php artisan make:filament-user` also works, but it's interactive and
   doesn't play nicely in some terminals — the tinker one-liner is more
   reliable.)

### If you're switching an *existing* clone from SQLite to MySQL

If you already had this project running on SQLite from before this change:

1. Pull the latest `.env.example` (`git pull`, or diff it against your `.env`
   manually) — it now has `DB_CONNECTION=mysql` and the connection block
   above instead of `DB_CONNECTION=sqlite`.
2. Update your own `.env` to match (steps 3–5 above — create the database,
   copy the `DB_*` lines into your `.env`, run migrate/seed, recreate your
   admin user since it's a fresh database).
3. Your old `database/database.sqlite` file is now unused and gitignored —
   safe to delete, or just leave it.

## 4. Run it

```bash
php artisan serve
```

- API: `http://localhost:8000/api/v1/home`, `http://localhost:8000/api/v1/settings`
- Admin panel: `http://localhost:8000/admin`
- API docs (Scramble, auto-generated OpenAPI): `http://localhost:8000/docs/api`

Point the frontend at it via `frontend/.env.local`:
```
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
```

## 5. Troubleshooting — real issues we already hit

### "Your requirements could not be resolved" / `ext-intl` missing on `composer require`

Composer error mentions `it is missing from your system. Install or enable
PHP's intl extension`, and `filament/support` (or similar) requires `ext-intl`.

Check which `php` is actually active first — **this is almost always the real
problem**, not a missing extension per se:

```bash
where php        # Windows
php -v
php --ini        # shows the loaded php.ini path
php -m           # lists loaded extensions — look for "intl"
```

We hit this because **Herd Lite**'s bundled `php.exe` is a minimal static
build with `intl` compiled out entirely — there's no `ext/` folder and no
`php_intl.dll` to enable, so it's simply impossible to fix via `php.ini`.
**Don't use Herd Lite for this project.** If `where php` points there,
switch your terminal to a full PHP install (Laragon's, XAMPP's, or full Herd).

If your active `php` does have an `ext/` folder with `php_intl.dll` in it,
just enable it in that php.ini:
```ini
extension_dir = "ext"
extension=intl
```
then verify with `php -m | grep -i intl`.

### `composer require filament/filament:^3.2` fails / only v3.x doesn't work

**Don't pin Filament to v3.** Laravel 13's `illuminate/auth` version isn't
satisfied by any Filament v3.x release (v3 caps at `illuminate/auth ^12.0`).
Just run `composer require filament/filament` with no version constraint —
it will resolve to v5.x, which is what this codebase is written against
(`Filament\Schemas\Schema`, not the old `Filament\Forms\Form` API). If you
also need `composer require filament/filament --with-all-dependencies` to
get past a "locked to version X" error, that's normal — it just means some
transitive dependency needs bumping too.

Also needed (not always obvious from the core Filament install):
```bash
composer require filament/spatie-laravel-media-library-plugin
```
This is what powers the image/photo upload fields on `InitiativeResource`
and `TestimonialResource` (`SpatieMediaLibraryFileUpload`,
`SpatieMediaLibraryImageColumn`) — easy to miss since it's a separate package
from `spatie/laravel-medialibrary` itself.

### PHP version is too old (e.g. XAMPP's bundled PHP 8.2)

Laravel 13 needs PHP ^8.3 across the board — not just the root
`composer.json`, but `laravel/framework`, `laravel/pint`, `laravel/pail`,
`phpunit/phpunit`, and `laravel/pao` too. Downgrading the `php` constraint in
`composer.json` will not work — `composer update` will still fail on the
already-locked packages. You need an actual PHP 8.3+ binary; there's no way
around it.

### Laragon: Apache/phpMyAdmin won't start, or `php artisan` uses the wrong PHP

Laragon installs can end up with **multiple version folders for the same
tool**, where one is a corrupted/incomplete download (a folder that exists
but is missing real binaries or config files inside it — e.g. an `apache`
version folder with an empty `conf/` directory, or a `php` version folder
containing only a leftover `php.ini` and no `.exe` files at all). This
happened to us with both a PHP version and an Apache version.

To diagnose:
```bash
# Does the PHP folder actually have php.exe, or just a php.ini?
ls "C:\laragon\bin\php\<version-folder>\"

# Does the Apache folder have a populated conf/ directory?
ls "C:\laragon\bin\apache\<version-folder>\conf\"

# Try starting Apache directly to see the real error (Laragon's own log
# often doesn't capture this):
cd "C:\laragon\bin\apache\<version-folder>\bin"
.\httpd.exe -t          # config syntax check
.\httpd.exe -X           # foreground debug run, Ctrl+C to stop
```

If a version folder is genuinely incomplete/corrupted, delete it — Laragon
will fall back to any other valid version of that same tool.

**Important**: Laragon regenerates its Apache config files (e.g.
`C:\laragon\etc\apache2\fcgid.conf`, which wires PHP into Apache via FastCGI)
from its own saved settings, not the other way around. If you manually edit
`fcgid.conf` to point at a working PHP folder and it keeps reverting back to
a broken one every time you click Start in the Laragon GUI, the real fix is
in:
```
C:\laragon\usr\profile\default.ini
```
Look for the `[php]` section's `Version=` line — that's Laragon's actual
source of truth for which PHP version to wire into Apache. Update it to
match a PHP version folder that's actually complete, then restart Apache via
the Laragon GUI (Stop All → Start All).

### `/api/v1/settings` (or any single-resource endpoint) returns `{"data": {...}}` instead of a flat object

Laravel auto-wraps a `JsonResource` returned directly from a controller in a
`data` envelope. This project's frontend (`frontend/src/lib/api.ts`) expects
flat shapes matching `frontend/src/lib/types.ts` exactly. This is already
fixed via `JsonResource::withoutWrapping();` in `AppServiceProvider::boot()`
— if you ever see this issue again, check that line is still there before
debugging further.

### Filament page/resource shows "Forbidden" (403) after logging in

Filament only allows panel access to any authenticated user when
`APP_ENV=local` (see `vendor/filament/filament/src/Http/Middleware/Authenticate.php`).
Check your `.env` has `APP_ENV=local`. In any other environment, the `User`
model needs to implement `Filament\Models\Contracts\FilamentUser` with a
`canAccessPanel()` method — not yet done in this codebase since it's still
local-dev only.
