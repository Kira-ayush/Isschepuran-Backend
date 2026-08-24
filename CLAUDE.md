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

**Site-wide** (not page-scoped, so all three sit outside every page-specific
nav group — same reasoning as `SeoSetting` above): `SiteSetting` (org info,
contact, nav links, social links, donate destination), `CtaBand` (the
closing "Join the Movement" band shown at the bottom of every page — it
used to live under the "Home Page" nav group since Home was the first page
built, but moved out once About and Initiatives started rendering it too;
if a future page also needs it, no change required, it already applies
everywhere), and `SdgAlignment` (the 9-row SDG table — renders on both Home
and Impact from day one, built with this placement from the start rather
than starting under one page's nav group and moving later).

**Home** (4 types): `Hero` (singleton, has CTAs + background image),
`ImpactStat`, `Initiative` (pillars — has `category_id` FK +
`featured_on_home` bool — see `Category` below, this was originally a
fixed enum), `Testimonial`.

**About** (6 types): `AboutHero` (singleton, no CTAs — matches actual source
content), `AboutIntro` (singleton — origin story + vision/mission),
`GeographicReach`, `AboutMilestone` ("Journey of Impact" — **now
deliberately reused on the Impact page too** (see Impact below); this
supersedes an earlier version of this note that said not to reuse it,
written when the two pages' original site content genuinely contradicted
each other on geography/timeline. That contradiction was resolved by
standardizing on this real Cyclone-Yaas-founded timeline everywhere instead
of keeping two conflicting ones — the original Impact page's placeholder
"Satpura range" content was never migrated), `TeamMember` (has the same
real-photo-only rule as `Testimonial` — see below), `TrustBadge`.

**Initiatives** (reuses Home's `Initiative` model, doesn't add a new content
type): `InitiativesHero` (singleton, no CTAs). The full listing pulls from
the same `initiatives` table Home's featured pillars use, just unscoped
(`Initiative::published()->get()` vs. `featuredOnHome()`) and through a
different, fuller Http Resource — `InitiativeListResource` (adds `body`)
alongside the original `InitiativeResource` (Home's lean shape). **When the
same model needs a leaner shape for one page and a fuller one for another,
add a second Http Resource + controller + route rather than adding fields
to the existing one** — keeps Home's `/pillars` contract exactly as
documented in `frontend/src/lib/types.ts`'s `Pillar` instead of growing an
unused field onto it. `InitiativeResource` (the Filament admin resource)
moved from the `Home Page` nav group to `Initiatives Page` once a second
page started depending on it — same "group by actual usage, not
by-page-that-happened-to-need-it-first" rule as `SectionHeading` list
sections. Also has its own detail endpoint, `GET /api/v1/initiatives/{slug}`
(404 JSON response for an unknown slug, not a Laravel error page — the
frontend's `getInitiative()` checks for `status === 404` specifically to
call Next's `notFound()`).

**`Category`** — the master list of Initiative categories (Environment,
Water, Community today, but meant to grow/shrink from the admin panel
without a code change). Own Filament resource under Initiatives Page →
Categories: `name`, `slug`, `color` (a Select restricted to a fixed set of
approved design-token keys — `forest`/`sage`/`mustard`/`forest-dark` — NOT
a free color picker, keeps new categories on-brand automatically; add new
options to both `CategoryResource::COLOR_OPTIONS` (backend) and
`frontend/src/lib/categoryColors.ts`'s `categoryColorClasses` map if the
approved palette ever grows), `order`, `is_published`. `Initiative.category`
was originally a 3-value enum column — converted to `category_id` FK
belongsTo `Category` (see migration history below if a similar enum→FK
conversion is needed elsewhere). Deleting a Category still assigned to
initiatives is blocked with a friendly notification
(`EditCategory::getHeaderActions()`'s `DeleteAction->before()` + `->cancel()`)
rather than a raw foreign-key-constraint database error — the FK itself is
`restrictOnDelete()`, so without this UI guard it would throw.

Every Http Resource that includes an initiative (`InitiativeResource`,
`InitiativeListResource`) nests `category` as `new CategoryResource($this->category)`
— `{slug, name, color, order}` — not a bare string. Controllers that query
initiatives eager-load it (`Initiative::with('category')->...`) to avoid
N+1s; a new controller returning initiatives should do the same.

**Impact** (5 new types + reuses 3 existing ones): `ImpactHero` (singleton,
no CTAs/image — no source copy existed for this page in
`docs/raw-site-content.md`, so its seeded default is drafted copy, flagged
as such in `ImpactPageSeeder`'s docblock, not migrated real content),
`CsrFeature` (3 fixed feature bullets, curated icon `Select` like
`ImpactStatResource` rather than free text — a small fixed set, unlike
`Initiative.icon`'s many-arbitrary-icons case), `CsrPartner` (media +
`logo_alt`, **seeded with zero rows** — the original site's 3 partner names
read as obviously fake placeholders, not real ones, so nothing was migrated
or invented; frontend renders nothing for this sub-block until real
partners exist), `CarbonStat` (year/tons/`is_projected`). Reuses
`ImpactStat`/`/impact-stats` (same 6 stat categories as Home, no new type),
`AboutMilestone`/`/about-milestones`, and `Testimonial`/`/testimonials` —
see the About section above and `TestimonialResource\Pages\ListTestimonials`
for why these are shared data with independently-editable per-page framing.
`SdgAlignment` (see "Site-wide" above) also renders here. CSR content
(`CsrFeature`/`CsrPartner`/`CarbonStat`) shares **one** section-heading key
(`csr-synergy`, registered only on `CsrFeatureResource`'s list page — the
other two list pages' `getHeaderWidgets()` deliberately return nothing, see
their class comments) since the source content presents them as one
section, not three.

**Gallery** (3 new types): unlike every other page, the original site's
Gallery was **completely empty** ("nav and footer only") — a from-scratch
build, no copy to migrate anywhere including hero copy (`GalleryHero`'s
seeded default is drafted, same flagging convention as `ImpactHero`).
`GalleryItem` (media + `image_alt`, nullable `caption`, `order`,
`is_published`) has a `category_id` FK to `GalleryCategory` — **originally
built as a plain string + curated 3-option Select** (the CMS spec
hardcodes exactly 3 values with no growth signal, so it matched
`CsrFeature.icon`'s precedent rather than `Initiative.category_id`'s), then
**converted to a full category master** per an explicit user request to
mirror Initiative's admin-managed add/remove/reorder pattern exactly —
`GalleryCategoryResource` is a close copy of `CategoryResource`
(`name`/`slug`/`color` restricted to the same approved palette/`order`/
`is_published`, same delete-protection guard on `EditGalleryCategory`).
**Gotcha hit during the conversion**: `GalleryCategory::galleryItems()`
needed an **explicit** `hasMany(GalleryItem::class, 'category_id')`
foreign key — without it, Eloquent infers `gallery_category_id` from the
model's own class name, which doesn't match the actual `category_id`
column (this only surfaced as a 500 on the category list page's
`->counts('galleryItems')` column, not at migrate/seed time — another
render-time-only bug the "render-test, don't trust migrate" habit caught).
The migration sequence is the same enum→FK pattern as Initiative's (see
below), this time with the old column's `dropColumn()` included in the
finalize migration from the start rather than left out. One
`gallery-items` section-heading key covers the whole grid — this isn't
grouped-by-category server-side like Initiatives, just one grid with
**client-side filter tabs** (`GalleryGrid.tsx` on the frontend, dynamically
built from `GalleryCategory` — a tab is only shown for a category that
currently has at least one published item, "General" starts hidden since
it has zero), a genuinely new pattern in this codebase, alongside a
click-to-open **lightbox** (`GalleryLightbox.tsx`, later enhanced with
prev/next navigation via buttons, arrow keys, and touch-swipe, all
wrapping at the ends and scoped to whatever filter is active) — both built
with plain React state + Tailwind, no new npm dependency. `GalleryItem`
also has an `is_featured` boolean (which image shows in the large banner
above the grid) — an explicit admin toggle, not an implicit "lowest
`order`" convention. **At most one row may be `is_featured` at a time,
enforced in `GalleryItem::booted()`'s `saving` hook** (setting one row
featured directly un-features every other row via a plain query update,
not a cascade of model events) rather than relying on form-level UI
discipline or a DB constraint (a partial unique index wasn't judged worth
the portability cost for one boolean flag on a handful of rows).
`GalleryPageSeeder` is also
the **first seeder in this project to attach a real uploaded file**
programmatically (`addMedia($path)->preservingOriginal()
->toMediaCollection('image')`) rather than a manual admin-panel upload —
guarded with `is_file($path)` (skips + logs a warning rather than crashing
`db:seed` if a teammate doesn't have the local client asset pack) and
idempotent on re-run (`getMedia('image')->isNotEmpty()` skip check). Any
future seeder needing to attach a real file can copy this pattern.

**Get Involved / Contact** (9 new types, plus the first public write
endpoints, first payment integration, and first encrypted secrets in this
project): `GetInvolvedHero`/`ContactHero` (same minimal singleton shape as
every other hero). `DonationMethod` (static bank/UPI/international
details — real content, seeded from `docs/raw-site-content.md`; distinct
from the Razorpay integration below). `PaymentSetting` — singleton,
Razorpay `key_id`/`key_secret`/`webhook_secret` entered via the admin
panel (`ManagePaymentSettings`), **not `.env`**, so the client can
self-serve their own credentials without a developer redeploying.
`key_secret`/`webhook_secret` use Laravel's `'field' => 'encrypted'` cast
— first use of it in this project — and the migration column type is
**`text`, not `string(255)`**: encrypted output (AES-256-CBC + base64) is
well over 255 chars even for a short secret, a first-use gotcha worth
remembering for any future encrypted field. These two fields must **never**
appear in any public API Resource — `create-order`'s own JSON response
returns `keyId` directly (the public key Checkout.js needs client-side)
instead of a public settings endpoint ever existing.

`Donation` tracks the actual Razorpay payment lifecycle
(`pending|paid|failed|refunded`), created at `create-order` time and
flipped by **either** `DonationController::verify()` (the client-side
round-trip after Checkout completes) **or** `RazorpayWebhookController`
(Razorpay's own server-to-server event feed) — both idempotent, order
doesn't matter, since the webhook exists specifically to bring a
`Donation` to its correct final state even when the browser closes before
`verify()` fires. **These are two genuinely different signature checks**
using the official `razorpay/razorpay` SDK: `Utility::verifyPaymentSignature()`
(the 3-field order/payment/signature check, needs `Api::getSecret()`
populated by constructing `new Api($key, $secret)` first — `Utility`
pulls the secret from `Api`'s static state, not a constructor arg) for
`verify()`, vs. `Utility::verifyWebhookSignature($rawBody, $signature, $webhookSecret)`
(raw-request-body HMAC) for the webhook — treating these as interchangeable
is a common real integration bug. Amounts are stored in **rupees** in
`donations.amount` (human-readable in the admin table) and converted to
**paise** only at the `$api->order->create()` call boundary
(`(int) round($amount * 100)`) — the single most common real Razorpay bug.
Verified end-to-end against Razorpay's real test-mode API (not mocked):
a real order created, a hand-computed valid signature accepted, an
invalid one rejected with the donation marked `failed`, and the webhook
path independently verified the same way with its own signature scheme.

`VolunteerApplication`/`CsrInquiry`/`ContactSubmission`/`NewsletterSubscriber`
are the first public POST endpoints in this project — validated via
`FormRequest` classes (first use of those too), camelCase request bodies
mapped explicitly to snake_case columns in each controller. All 6 public
write routes (these 4 plus `donations/create-order`+`verify`) share a
**named** rate limiter (`RateLimiter::for('public-forms', ...)` in
`AppServiceProvider::boot()`), not the bare `throttle:5,1` shorthand —
that shorthand keys by IP+domain only
(`Illuminate\Routing\Middleware\ThrottleRequests::resolveRequestSignature()`),
so every route sharing that middleware would draw from **one pooled
bucket per IP** instead of each endpoint getting its own 5/minute limit —
confirmed by testing (a burst against one endpoint was incorrectly
blocking submissions to a completely different endpoint until this was
keyed by `IP + request path` instead). The Razorpay webhook sits outside
this limiter entirely, with its own looser `throttle:60,1`, since it's
server-to-server from Razorpay and shouldn't share a budget with public
form traffic — bundling it in risks 429-ing Razorpay's own retry bursts.

New Filament nav group **`"Submissions"`** houses `Donation` (no `create`
page — donations only ever originate from the Razorpay flow, never a
manual admin entry) plus the 4 write-form resources — "data the site
collects," a different kind of admin screen from every other nav group
("content the admin authors").

**Admin panel branding + Dashboard widget** — `AdminPanelProvider::panel()`
sets `->brandName('Ichhe Puran')` (Filament defaults to "Laravel" on both
the login page and sidebar if this isn't set — easy to miss since nothing
errors, it just looks unbranded). The stock `Dashboard` page's default
`FilamentInfoWidget` (Filament's own version/GitHub links — meaningless to
a content admin, and the reason the dashboard looked "blank/dull" before
this) was swapped for a hand-written `App\Filament\Widgets\SubmissionsOverviewWidget`
(extends `StatsOverviewWidget`, six `Stat::make()` cards: new Volunteer
Applications, new CSR Inquiries, unread Contact Messages, Newsletter
Subscribers, Pending Donations, Total Raised — each links via `->url()` to
its Submissions resource) registered in `->widgets([AccountWidget::class,
SubmissionsOverviewWidget::class])`, replacing `FilamentInfoWidget::class`.
`php artisan make:filament-widget --stats-overview` hangs on an interactive
panel-selection prompt in a non-interactive shell — faster to read
`vendor/filament/widgets/src/StatsOverviewWidget.php` directly and
hand-write the class than fight the scaffold command. Verified via
Playwright screenshot logged in as a throwaway admin user (deleted after) —
not just a render-test, since the whole point was checking the *visual*
result, not just that the page returns 200.

**Email notifications were deliberately not built** for any of this (the
4 write forms or the donation receipt) — an explicit user decision, not an
oversight. Submissions are visible only via the admin panel's Submissions
nav group. If this gets added later: this project's `QUEUE_CONNECTION` is
set to `database` in `.env`, but **no `jobs` table migration exists
anywhere** — don't assume `Mail::...->queue()` "just works" because the
env var is set; either run `php artisan queue:table && php artisan migrate`
first, or use synchronous `Mail::send()` instead (probably the simpler
right call at this traffic volume — these are low-frequency form/receipt
emails, not bulk sending).

**Converting a fixed enum column to an admin-managed master (FK) — the
migration sequence that worked cleanly:** (1) create the master table: (2) add
the new `_id` FK column as **nullable**, keep the old enum column in place;
(3) a seeder backfills the FK from the old column's values and creates the
initial master rows (`CategorySeeder`, called from any content seeder that
references categories — safe to call repeatedly, `updateOrCreate`); (4) a
separate migration drops the old column and makes the FK `NOT NULL`. Doing
the drop+NOT NULL in the same migration as adding the column will lose the
backfill step entirely. Also: a `nullOnDelete()` FK **cannot** be changed to
`NOT NULL` directly — MySQL rejects it ("column cannot be NOT NULL: needed
in a foreign key constraint ... SET NULL") — drop and re-add the constraint
with a different `onDelete()` (we used `restrictOnDelete()`) in that same
finalize migration, before the `NOT NULL` change. MySQL also doesn't run
migrations transactionally, so a failed finalize migration can leave the
table in a partially-changed state (in our case the enum column was already
dropped when the FK-constraint step failed) — check the actual schema
(`DESCRIBE table`) before assuming a failed migration changed nothing. **This
bit us for real**: the finalize migration's `up()` originally only handled
the FK/`NOT NULL` change and never explicitly dropped the old `category`
column — it "worked" on the production MySQL DB only because that column had
already been dropped as a side effect of an earlier failed attempt (see
above). A from-scratch migrate (a fresh dev machine, or the test suite's
`RefreshDatabase` against SQLite) still had the original NOT NULL enum
column and failed every `Initiative::create()`. Fixed by adding an explicit
`if (Schema::hasColumn(...)) { dropColumn(...) }` guard to that migration's
`up()`. Lesson: after any migration sequence where one step's success
depends on state left behind by an earlier *failed* run, re-verify it also
works against a genuinely fresh database, not just the already-patched one.

**Per-page SEO Settings** — a dedicated admin screen per top-level page
(`ManageHomeSeo`, `ManageAboutSeo`, `ManageInitiativesSeo` — nav label "SEO
Settings" under each page's existing nav group), on top of the
`generateMetadata()`-from-content-fields approach already in place. Backed
by one generic `SeoSetting` model (`meta_title`, `meta_description`,
`og_title`, `og_description`, `og_image` media collection, `twitter_title`,
`twitter_description`, `twitter_image` media collection), keyed by a fixed
page key (`home`/`about`/`initiatives`) the same way `SectionHeading` is
keyed — `SeoSetting::forKey($key)`. All three Page classes share their
form/mount/save via one trait, `App\Filament\Pages\Concerns\ManagesSeoSettings`
(`abstract public function seoKey(): string` is the only thing each concrete
page implements) — this is the one place in the codebase that departs from
the usual "copy-paste a whole singleton Page class" convention, because
these three are genuinely byte-for-byte identical apart from the key/nav
properties. `GET /api/v1/seo-settings/{key}` uses an allowlist
(`SeoSettingController::KEYS`) exactly like `SectionHeadingController::DEFAULTS`
— add the new key there (and to the trait's usage) before wiring up SEO
Settings for a future page. **Every field is optional and every field
individually falls back** on the frontend to that page's own
content-derived title/description/image when unset (see
`frontend/src/lib/seo.ts`) — an admin can override just the OG image while
leaving everything else on defaults. Canonical URL is deliberately NOT a
field on this model — see the frontend section for why.

**Alt text fields** — every image-bearing content type (`Hero.background_alt`,
`AboutIntro.origin_image_alt`, `AboutMilestone.image_alt`, `Initiative.image_alt`,
`SectionHeading.image_alt`, `TeamMember.photo_alt`, `Testimonial.photo_alt`)
has a plain nullable `string` column alongside its media collection, editable
via a `TextInput` right after the corresponding `SpatieMediaLibraryFileUpload`
field, and exposed on the matching Http Resource as a camelCase key
(`backgroundImageAlt`, `originImageAlt`, `imageAlt`, `photoAlt`). Unlike the
media field itself, alt-text columns are plain mass-assignable fields — they
do NOT need `unset($state[...])` before `->update($state)`/`saveRelationships()`
in a custom Page/Widget's `save()`, only the upload field's own key does.
When adding a new image field to any content type, add its `_alt` sibling
column in the same migration rather than bolting it on later.

Plus the generic `SectionHeading` (see above) powering section titles on
every list-style resource across both pages. `SectionHeading` also supports
an optional `image` media collection (most sections don't set one — it's
for section-level visuals like Geographic Reach's infographic map, not
per-item images).

`AboutIntro` and `AboutMilestone` also each got an optional image field
(`origin_image` / `image`) added after the fact — a real content asset pack
arrived mid-build and these didn't have media support yet. Follow this
pattern (add `HasMedia`/`InteractsWithMedia` + `useDisk('public')`, then a
`SpatieMediaLibraryFileUpload` field, then `getFirstMediaUrl()` in the Http
Resource) any time a text-only content type turns out to need an image
later — no migration needed, media lives in the shared polymorphic table.

**Custom Filament pages/widgets with a file-upload field must bind the
model before `fill()` in `mount()`, not just pass `->toArray()`:**
`$this->form->model($record)->fill($record->toArray())`, not
`$this->form->fill($record->toArray())`. Without the model bound,
`SpatieMediaLibraryFileUpload` has no record to look up existing media
against, so a previously-uploaded file won't preview when reopening the
page (the upload itself still saves fine via `saveRelationships()` in
`save()` — this only affects the admin form's preview, not data
correctness, but it's confusing enough to always get right). Applies to
`ManageHero`, `ManageAboutIntro`, `SectionHeadingWidget` — anywhere a
custom Page/Widget (not a Resource's own Create/EditRecord, which handles
this automatically) has an upload field.

**Custom error pages** exist at `resources/views/errors/{404,403,419,500,503}.blade.php`
(sharing `errors/layout.blade.php`), styled with the site's color tokens.
These only render when `APP_DEBUG=false` — Laravel prefers the full debug
page over custom error views whenever debug is on, which is correct for
local dev (don't be confused if a change here doesn't visibly do anything
locally).

## What's next

All pages are now built (Home, About, Initiatives, Impact, Gallery, Get
Involved, Contact) — see "What's built" above for the full content-type
inventory (29 types total across all pages, plus the public write
endpoints and Razorpay integration). Remaining known gaps, not pages:
1. Email notifications (deliberately skipped per user decision — see the
   "Get Involved / Contact" note above for what to check before adding
   this, specifically the missing `jobs` table if `->queue()` is used).
2. Real end-to-end Razorpay testing was done in test mode with the user's
   own credentials — going live just means entering live-mode credentials
   via `/admin/payment-settings` and configuring the real webhook URL
   (`/api/v1/webhooks/razorpay`) + its secret on Razorpay's dashboard.
   No code change needed for that transition.
3. Scramble's `/docs/api` — installed, not yet verified that it actually
   renders (see Environment specifics in the root CLAUDE.md).

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

**Also always add `->maxSize(10240)`** (10MB, in KB — Filament's unit) to
every `SpatieMediaLibraryFileUpload` field. Without it, a file over Spatie's
own hard-coded 10MB limit throws an uncaught `FileIsTooBig` exception
straight to a raw error page instead of Filament's normal inline validation
message — this happened once already. `maxSize()` makes Filament validate
(and show a clean field error) before the file ever reaches Spatie.

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
