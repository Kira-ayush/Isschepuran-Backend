<?php

namespace Database\Seeders;

use App\Models\DonationMethod;
use App\Models\GetInvolvedHero;
use App\Models\SectionHeading;
use Illuminate\Database\Seeder;

/**
 * Seeds the Get Involved page — Donate (Razorpay + static donation
 * methods), Volunteer form, CSR partnership form, all on one page with
 * anchor sections (#donate/#volunteer/#csr, matching the already-seeded
 * CTA hrefs in HomePageSeeder.php).
 *
 * Hero copy is real, migrated content — docs/raw-site-content.md's
 * Contact/Donate page intro. The 3 DonationMethod rows are also real
 * content (bank/UPI/international details already documented there), not
 * placeholders. QR image is seeded empty — none exists in the client
 * asset pack — admin uploads it later via the DonationMethod edit form.
 *
 * Run with: php artisan db:seed --class=GetInvolvedPageSeeder
 */
class GetInvolvedPageSeeder extends Seeder
{
    public function run(): void
    {
        GetInvolvedHero::current()->update([
            'headline' => 'Your Contribution Sows the Seeds of Change',
            'subheading' => 'Whether you choose to donate financially, volunteer your time, or partner with us through CSR, every effort helps us restore nature and empower communities.',
        ]);

        $sectionHeadings = [
            'donation-methods' => ['eyebrow' => 'Ways to give', 'heading' => 'Choose How to Give'],
            'volunteer' => ['eyebrow' => 'Give your time', 'heading' => 'Volunteer With Us'],
            'csr-partnership' => ['eyebrow' => 'Partner with purpose', 'heading' => 'CSR & Corporate Partnerships'],
        ];
        foreach ($sectionHeadings as $key => $h) {
            SectionHeading::forKey($key, $h['eyebrow'], $h['heading'])
                ->update(['eyebrow' => $h['eyebrow'], 'heading' => $h['heading']]);
        }

        DonationMethod::updateOrCreate(['type' => 'bank'], [
            'title' => 'Bank Transfer',
            'order' => 1,
            'is_published' => true,
            'fields' => [
                'Account Name' => 'Ichhe Puran Trust',
                'Bank' => 'National Green Bank',
                'A/C No.' => '987654321012',
                'IFSC' => 'NGRB0001234',
                'Branch' => 'Eco Park, Kolkata',
            ],
            'instructions' => 'All donations are tax-deductible under Section 80G.',
        ]);

        DonationMethod::updateOrCreate(['type' => 'upi'], [
            'title' => 'UPI',
            'order' => 2,
            'is_published' => true,
            'fields' => [
                'UPI ID' => 'ichhepuran@upi',
            ],
            'instructions' => 'Scan the QR code (once uploaded in the admin panel) or pay directly to the UPI ID above. All donations are tax-deductible under Section 80G.',
        ]);

        DonationMethod::updateOrCreate(['type' => 'international'], [
            'title' => 'International Donors',
            'order' => 3,
            'is_published' => true,
            'fields' => [],
            'instructions' => 'For supporters outside India, we accept wire transfers and PayPal. Please email donations@ichhepuran.org for FCRA details.',
        ]);
    }
}
