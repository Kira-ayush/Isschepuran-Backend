<?php

namespace Database\Seeders;

use App\Models\CarbonStat;
use App\Models\CsrFeature;
use App\Models\ImpactHero;
use App\Models\SdgAlignment;
use App\Models\SectionHeading;
use Illuminate\Database\Seeder;

/**
 * Seeds the Impact page. Two of its sections deliberately reuse existing
 * content instead of migrating the original site's copy — see
 * docs/raw-site-content.md's "Impact page" section and the client
 * decisions recorded in the Impact page implementation plan:
 *
 * - "Journey of Impact" timeline: reuses AboutMilestone (the real Cyclone
 *   Yaas founding story already on the About page), NOT the original
 *   Impact page's "Satpura range" (Madhya Pradesh) content, which read as
 *   unedited template placeholder and geographically contradicted the
 *   org's real West Bengal/Jharkhand/Odisha operating area. This
 *   SUPERSEDES an earlier note in backend/CLAUDE.md saying not to reuse
 *   AboutMilestone for Impact — that note predates this decision.
 * - "Faces of Impact" testimonials: reuses Testimonial (Saraswati Devi,
 *   Rahul Mondal — real, already-verified content), NOT the original 3
 *   placeholder names (wrong locations/roles, no other trace on the site).
 * - Neither of the above needs seeding here — ImpactPageSeeder must run
 *   AFTER HomePageSeeder/AboutPageSeeder so those rows already exist; it
 *   only seeds the section-heading copy for how they're framed on Impact.
 *
 * SDG 6's contribution_text uses "10M+ Liters Recharged" (the Initiatives
 * page's Project Amrit Kund figure), NOT the original raw content's
 * "4.5M litres restored" — the two contradicted each other for the same
 * project; the client picked the Initiatives page's figure as canonical.
 *
 * CsrPartner is intentionally seeded with ZERO rows. The 3 names in the
 * raw source content ("FINTECH"/"ECOBUILD"/"AGROCORP") read as obviously
 * fake placeholder company names, not real client-confirmed partners —
 * per this project's hard rule against fabricated content, they are not
 * migrated or invented. The section stays structurally built and
 * admin-fillable; the frontend renders nothing for this sub-block until
 * real partners are added.
 *
 * Run with: php artisan db:seed --class=ImpactPageSeeder
 */
class ImpactPageSeeder extends Seeder
{
    public function run(): void
    {
        // No hero copy exists for Impact in docs/raw-site-content.md (the
        // original live page had no documented hero section) — this is
        // drafted copy in the established voice, not migrated real
        // content. Flagged here so it's easy to find and revise later.
        ImpactHero::current()->update([
            'headline' => 'Measuring What Matters',
            'subheading' => 'From verified SDG contributions to transparent carbon reporting, every number on this page traces back to a real project on the ground.',
        ]);

        $sdgs = [
            ['sdg_number' => 2, 'goal_name' => 'Zero Hunger', 'contribution_text' => 'Food distribution, agroforestry, lassi drive, nutrition support for 3,000+ beneficiaries.', 'order' => 1],
            ['sdg_number' => 3, 'goal_name' => 'Good Health & Well-being', 'contribution_text' => 'Medical camps, mosquito nets, raincoat drives, health awareness sessions.', 'order' => 2],
            ['sdg_number' => 4, 'goal_name' => 'Quality Education', 'contribution_text' => 'Kriti Pathshala for migrant children, school plantation partnerships.', 'order' => 3],
            ['sdg_number' => 6, 'goal_name' => 'Clean Water & Sanitation', 'contribution_text' => 'Project Amrit Kund — 50+ Lakes Restored, 10M+ Liters Recharged; pond restoration in Saheban Bagicha.', 'order' => 4],
            ['sdg_number' => 10, 'goal_name' => 'Reduced Inequalities', 'contribution_text' => 'Khushir Pujo for 1,455+ marginalised children, welfare support for outdoor workers.', 'order' => 5],
            ['sdg_number' => 13, 'goal_name' => 'Climate Action', 'contribution_text' => '14,000+ trees planted, Miyawaki forests, mangrove restoration.', 'order' => 6],
            ['sdg_number' => 14, 'goal_name' => 'Life Below Water', 'contribution_text' => 'Pond restoration.', 'order' => 7],
            ['sdg_number' => 15, 'goal_name' => 'Life on Land', 'contribution_text' => 'Agroforestry, mangrove restoration, Miyawaki forests, biodiversity enhancement.', 'order' => 8],
            ['sdg_number' => 17, 'goal_name' => 'Partnerships for the Goals', 'contribution_text' => 'Manpower, consultation, collaboration, and expertise.', 'order' => 9],
        ];
        foreach ($sdgs as $s) {
            SdgAlignment::updateOrCreate(['sdg_number' => $s['sdg_number']], $s);
        }

        $features = [
            ['title' => '100% Transparency', 'description' => 'Blockchain-backed tree tracking for every corporate donation', 'icon' => 'ShieldCheck', 'order' => 1],
            ['title' => 'Real-time Dashboard', 'description' => 'Custom impact portals for partners to track carbon sequestration', 'icon' => 'LayoutDashboard', 'order' => 2],
            ['title' => 'Eco-Audit Reports', 'description' => 'Quarterly scientific reports detailing biodiversity net gain', 'icon' => 'FileCheck2', 'order' => 3],
        ];
        foreach ($features as $f) {
            CsrFeature::updateOrCreate(['title' => $f['title']], $f);
        }

        $carbonStats = [
            ['year' => '2022', 'tons' => 420, 'is_projected' => false, 'order' => 1],
            ['year' => '2023', 'tons' => 1150, 'is_projected' => false, 'order' => 2],
            ['year' => '2024', 'tons' => 2800, 'is_projected' => true, 'order' => 3],
        ];
        foreach ($carbonStats as $c) {
            CarbonStat::updateOrCreate(['year' => $c['year']], $c);
        }

        // CsrPartner: deliberately not seeded — see class docblock.

        $sectionHeadings = [
            'impact-milestones' => ['eyebrow' => 'Our journey', 'heading' => 'Journey of Impact'],
            'impact-testimonials' => ['eyebrow' => 'Voices of impact', 'heading' => 'Faces of Impact'],
            'sdg-alignment' => ['eyebrow' => 'Global commitments', 'heading' => 'Aligned with the UN Sustainable Development Goals'],
            'csr-synergy' => ['eyebrow' => 'Partner with purpose', 'heading' => 'Corporate Social Synergy'],
        ];
        foreach ($sectionHeadings as $key => $h) {
            SectionHeading::forKey($key, $h['eyebrow'], $h['heading'])
                ->update(['eyebrow' => $h['eyebrow'], 'heading' => $h['heading']]);
        }
    }
}
