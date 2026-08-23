# Backend — Ichhe Puran (Laravel + Filament)

Full project background: `../CLAUDE.md` (workspace root) and `../docs/`.
This file is the backend-specific detail that matters when you're working
in here specifically.

## Unresolved from last session — check this before anything else

Composer resolved **Filament v4 or v5** (Laravel 13 here, Filament v3 only
supports up to Laravel 12). The Filament Resource files below were written
against the **v3 Forms/Tables API**, which v4 restructured into a unified
Schema system. Run `composer show filament/filament` and check that version's
real docs before assuming any Filament code in this folder is correct —
don't guess, don't pattern-match from v3 memory.

## The vertical-slice pattern — follow this for every new content type

Every content type needs all six pieces, in this order:

1. `database/migrations/xxxx_create_x_table.php`
2. `app/Models/X.php` — add `HasMedia`/`InteractsWithMedia` only if it has images
3. `app/Filament/Resources/XResource.php` + `XResource/Pages/{List,Create,Edit}X.php`
   (the three Page classes are boilerplate — same shape every time, see any
   existing resource for the template)
4. `app/Http/Resources/XResource.php` — **the JSON shape here must match
   the corresponding interface in `../frontend/src/lib/types.ts` exactly**,
   camelCase keys included. Check the frontend type first, write the Resource
   to match it — not the other way around.
5. `app/Http/Controllers/Api/XController.php`
6. A route in `routes/api.php` under the `v1` prefix

Add seed data to `database/seeders/HomePageSeeder.php` (or a new seeder if the
content type isn't part of the home page) so the admin panel isn't empty on
first run.

## What's built (4 of ~14 content types)

`SiteSetting` (singleton — see `app/Filament/Pages/ManageSiteSettings.php`,
not a Resource, edited from its own settings page), `ImpactStat`, `Initiative`
(Core Pillars — has `category` enum + `featured_on_home` bool),
`Testimonial`.

## What's next — full endpoint checklist

See `../docs/project-plan.md` for the complete list (~16 more endpoints).
Priority order once the Filament version is sorted:
1. `Team`, `Timeline`/Milestones, `Gallery`, `SdgAlignment`, `CsrPartner`,
   `DonationMethod` content types (read-only, same 6-step pattern above)
2. `VolunteerApplication` / `CsrInquiry` / `NewsletterSubscriber` — these are
   write endpoints (form POSTs), need validation + a notification email, no
   Filament Resource strictly required for create (only for the admin to
   view/manage submissions)
3. Razorpay: `POST /donations/create-order`, `POST /donations/verify`
   (signature verification — **never trust the frontend's "payment succeeded"
   claim, always verify server-side against Razorpay's signature**),
   `POST /webhooks/razorpay`

## Hard rule specific to this side: media uploads

`TestimonialResource.php` and `InitiativeResource.php` use
`SpatieMediaLibraryFileUpload`. For `Testimonial` specifically, the helper
text on the `photo` field is load-bearing, not decorative — never remove the
warning about not uploading AI-generated images for named real people. If you
add a `TeamMember` resource later, carry the same warning over.

## Environment

Windows + Laravel Herd. **SQLite** for local dev
(`database/database.sqlite`) — don't switch to MySQL without the user asking,
even though production will use MySQL per the project plan. `ext-intl` had to
be manually enabled in Herd's php.ini once already.

## Testing status

Everything in this folder was syntax-checked with `php -l` (30/30 passed) but
**never actually executed** — it was hand-written in an environment with no
Packagist access. Treat it as reviewed-for-correctness, not verified-working,
until `php artisan migrate` + `db:seed` + hitting the endpoints has actually
been done. Once the basics run clean, add real Pest/PHPUnit feature tests per
the project plan's testing recommendations — none exist yet.
