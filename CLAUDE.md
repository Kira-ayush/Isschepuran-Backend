# Backend — Ichhe Puran (Laravel + Filament)

Full project background: `../CLAUDE.md` (workspace root) and `../docs/`.
This file is the backend-specific detail that matters when you're working
in here specifically.

## Filament version — resolved, don't re-litigate

Composer resolves to **Filament v5.7.6** on this project (Laravel 13 +
PHP 8.3+). It uses a unified Schema system, not the v3 Forms/Tables API:

- `public static function form(Schema $schema): Schema` (not `Forms\Form`),
  returning `$schema->components([...])` (`->schema([...])` still works as
  an alias, but `components()` matches the current generator output).
- Layout components moved namespaces too — `Section`, `Fieldset`, `Grid`,
  `Tabs`, `Wizard` all live under `Filament\Schemas\Components\*`, NOT
  `Filament\Forms\Components\*`. This exact mistake (`Filament\Forms\Components\Section`)
  caused a real "Class not found" error in production once already —
  it only breaks when the page is actually *rendered* (the class reference
  is inside a method body), not at migrate/seed time or even at admin panel
  boot, so it can silently ship unless you render-test the specific page.
- `protected static string|BackedEnum|null $navigationIcon` and
  `protected static string|UnitEnum|null $navigationGroup` — both widened
  from `?string` in v3/v4. Plain string values ('heroicon-o-...', 'Home
  Page') still work fine, just the property type declaration must match
  exactly or it's a fatal error on class load.
- Plain form/table field components (`TextInput`, `Select`, `Toggle`,
  `TextColumn`, `IconColumn`, etc.) did NOT move — still
  `Filament\Forms\Components\*` / `Filament\Tables\Columns\*`.
- Actions (`CreateAction`, `EditAction`, `DeleteAction`, `BulkActionGroup`)
  live under the unified `Filament\Actions\*` namespace now.
- `Filament\Schemas\Components\Utilities\Set`/`Get` (not `Filament\Forms\Set`).
- `filament/spatie-laravel-media-library-plugin` is a **separate package**
  from `spatie/laravel-medialibrary` — install both, easy to miss the first.

**When in doubt about a class's current namespace**: generate a throwaway
resource/page/widget with `php artisan make:filament-resource`/`-page`/`-widget`
and read what it actually produces, rather than guessing from v3 memory or
outdated docs. Delete the scratch files after.

## The vertical-slice pattern — follow this for every new content type

Every content type needs all six pieces, in this order:

1. `database/migrations/xxxx_create_x_table.php`
2. `app/Models/X.php` — add `HasMedia`/`InteractsWithMedia` only if it has
   images, and if so, **explicitly call `->useDisk('public')`** on the
   collection in `registerMediaCollections()` (see the media-disk gotcha
   below — this is not optional, it silently breaks without it).
3. `app/Filament/Resources/XResource.php` + `XResource/Pages/{List,Create,Edit}X.php`
   (the three Page classes are boilerplate — same shape every time, see any
   existing resource for the template). For a true singleton (only one row
   ever, like `SiteSetting`/`Hero`), use a `Filament\Pages\Page` with a
   `current()` model helper instead of a full Resource — see
   `ManageSiteSettings.php`/`ManageHero.php`/`ManageCtaBand.php`.
4. `app/Http/Resources/XResource.php` — **the JSON shape here must match
   the corresponding interface in `../frontend/src/lib/types.ts` exactly**,
   camelCase keys included. Check the frontend type first, write the Resource
   to match it — not the other way around.
5. `app/Http/Controllers/Api/XController.php`
6. A route in `routes/api.php` under the `v1` prefix — **one endpoint per
   section, not one combined per-page payload** (e.g. `/geographic-reach`,
   `/about-milestones`, not a single `/about` that returns everything) —
   this is the established pattern, keeps sections independently reusable
   and cacheable.

Add seed data to a per-page seeder (`HomePageSeeder.php`, `AboutPageSeeder.php`,
etc.) so the admin panel isn't empty on first run. **Use real content from
`docs/raw-site-content.md` when it exists for that page — don't invent
placeholder copy.** Read that doc's ⚠️ flags first; some content contradicts
itself across pages (e.g. About vs Impact's "Journey of Impact" timeline) and
needs a real decision, not a silent pick.

### List-style sections need a `SectionHeading`, not their own title field

For any content type that's a repeatable list (Pillars, Testimonials,
GeographicReach, AboutMilestone, TeamMember, TrustBadge — anything rendered
as a grid/list on the frontend with its own "eyebrow + heading" above it),
do NOT add `eyebrow`/`heading` columns to that resource's own table. Instead:

1. Register a `SectionHeadingWidget::make(['key' => '...', 'defaultEyebrow' => '...', 'defaultHeading' => '...', 'label' => '...'])`
   in that Resource's `ListRecords` page via `getHeaderWidgets()` (see
   `ListInitiatives`/`ListGeographicReaches` for the template) — this shows
   a small editable eyebrow+heading form right above that resource's table
   in the admin, using the generic `App\Models\SectionHeading` model (keyed
   by a string, one row per section).
2. Add the same key + defaults to `SectionHeadingController::DEFAULTS` —
   the API route `GET /api/v1/section-headings/{key}` 404s for unknown keys,
   so forgetting this step breaks the frontend fetch silently otherwise.
3. Seed the key too (`SectionHeading::forKey(...)`) in that page's seeder.

The frontend fetches it via the existing generic `getSectionHeading(key)` in
`lib/api.ts` — no new frontend API function needed, just a new key.

**`SectionHeadingWidget` has `protected static bool $isDiscovered = false;`
— do not remove it.** Any class in `app/Filament/Widgets/` is
auto-discovered by `AdminPanelProvider`'s `discoverWidgets()` and shown on
the **Dashboard** by default, instantiated with none of the properties a
`::make([...])` call would normally pass. Since `$key` has no default value,
that caused a real "must not be accessed before initialization" error the
first time this widget existed — from the Dashboard, not from any of the
actual list pages using it correctly. Any future widget that's meant to be
parametrized and used explicitly (not a general-purpose Dashboard widget)
needs this same flag.

## What's built

**Home** (6 types): `SiteSetting` (site-wide, singleton), `Hero` (singleton,
has CTAs + background image), `ImpactStat`, `Initiative` (pillars — has
`category` enum + `featured_on_home` bool), `Testimonial`, `CtaBand`
(singleton).

**About** (6 types): `AboutHero` (singleton, no CTAs — matches actual source
content), `AboutIntro` (singleton — origin story + vision/mission),
`GeographicReach`, `AboutMilestone` (About-specific "Journey of Impact" —
do NOT reuse for a future Impact-page timeline, they contradict each other
per the site audit), `TeamMember` (has the same real-photo-only rule as
`Testimonial` — see below), `TrustBadge`.

Plus the generic `SectionHeading` (see above) powering section titles on
every list-style resource across both pages.

## What's next — full endpoint checklist

See `../docs/project-plan.md` for the complete CMS content model (~14 types
total; 12 built so far across Home + About). Remaining, in likely priority
order:
1. Initiatives page (full list + detail, not just Home's featured 3),
   Impact page (own timeline — new content type, see the contradiction
   warning above), Gallery, SDG alignment rows, CSR partners, Donation
   methods — read-only, same 6-step pattern above.
2. `VolunteerApplication` / `CsrInquiry` / `NewsletterSubscriber` — these are
   write endpoints (form POSTs on the Get Involved/Contact pages), need
   validation + a notification email, no Filament Resource strictly required
   for create (only for the admin to view/manage submissions). Different
   pattern from everything built so far — don't force these into the
   read-only vertical slice above.
3. Razorpay: `POST /donations/create-order`, `POST /donations/verify`
   (signature verification — **never trust the frontend's "payment succeeded"
   claim, always verify server-side against Razorpay's signature**),
   `POST /webhooks/razorpay`

## Hard rule specific to this side: media uploads

Two things, both required, both silently break without the other:

1. **`->useDisk('public')`** on every `addMediaCollection()` call. Without
   it, uploads land on Laravel's private `local` disk
   (`storage/app/private`) and the resulting URL is dead — this actually
   happened once (an uploaded Initiative image returned a broken
   `/storage/...` URL because the collection defaulted to `local`).
2. **`php artisan storage:link`** must have been run once per environment —
   without the `public/storage` symlink, even a correctly-`public`-disk file
   is unreachable.

`TestimonialResource.php` and `TeamMemberResource.php` (and
`InitiativeResource.php` for its cover image) use `SpatieMediaLibraryFileUpload`.
The helper text on `Testimonial.photo` / `TeamMember.photo` warning against
AI-generated images for named real people is load-bearing, not decorative —
never remove it, and carry it over to any future person-photo field.

## Verification habit — render-test, don't just check it didn't throw on migrate

A class-not-found or type error inside a `form()`/`table()` method body only
surfaces when that specific page is actually rendered — `php artisan migrate`,
`db:seed`, and even the admin panel loading other pages will NOT catch it.
After adding/changing any Filament Resource, Page, or Widget: write a quick
throwaway PHPUnit feature test that does
`$this->actingAs($user)->get('/admin/the-actual-path')` and checks for a
non-500 status (Filament requires `APP_ENV=local` for panel access without a
`FilamentUser` implementation, so run with `APP_ENV=local php artisan test --env=local`
or the request 403s instead of exercising the real code path). Delete the
scratch test after — this isn't meant to become a permanent suite yet.

## Environment

**Windows + Laragon** (not Herd, not XAMPP — Herd Lite's PHP has `intl`
compiled out with no fix possible). PHP 8.3.30, **MySQL** (matches
production; switched from SQLite). See `../CLAUDE.md`'s Environment
specifics section and `SETUP.md` for the full detail and troubleshooting —
don't duplicate it here, just: MySQL must be started manually via the
Laragon GUI each session (it does not auto-start), check
`Test-NetConnection 127.0.0.1 -Port 3306` if `php artisan migrate` fails
with a connection-refused error.

## Testing status

No permanent automated test suite yet — verification has been done via
throwaway feature tests per content type (see "Verification habit" above),
deleted after confirming the page/endpoint works, plus manual `curl` checks
of every API endpoint. Once the CMS content model is more complete, add a
real Pest/PHPUnit suite per the project plan's testing recommendations.
