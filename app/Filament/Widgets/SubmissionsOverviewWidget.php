<?php

namespace App\Filament\Widgets;

use App\Models\ContactSubmission;
use App\Models\CsrInquiry;
use App\Models\Donation;
use App\Models\NewsletterSubscriber;
use App\Models\VolunteerApplication;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Replaces Filament's stock FilamentInfoWidget (just Filament's own
 * version/GitHub links, not useful for a content admin) with real,
 * site-specific activity — new/unread counts across the Submissions nav
 * group, so the Dashboard isn't just an empty welcome card.
 */
class SubmissionsOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $pendingDonations = Donation::where('status', 'pending')->count();
        $totalRaised = Donation::where('status', 'paid')->sum('amount');

        return [
            Stat::make('New Volunteer Applications', VolunteerApplication::where('status', 'new')->count())
                ->icon('heroicon-o-hand-raised')
                ->color('success')
                ->url('/admin/volunteer-applications'),
            Stat::make('New CSR Inquiries', CsrInquiry::where('status', 'new')->count())
                ->icon('heroicon-o-building-office')
                ->color('success')
                ->url('/admin/csr-inquiries'),
            Stat::make('Unread Contact Messages', ContactSubmission::where('status', 'new')->count())
                ->icon('heroicon-o-envelope')
                ->color('warning')
                ->url('/admin/contact-submissions'),
            Stat::make('Newsletter Subscribers', NewsletterSubscriber::where('status', 'subscribed')->count())
                ->icon('heroicon-o-envelope-open')
                ->color('gray')
                ->url('/admin/newsletter-subscribers'),
            Stat::make('Pending Donations', $pendingDonations)
                ->description($pendingDonations > 0 ? 'Awaiting payment confirmation' : 'All caught up')
                ->icon('heroicon-o-clock')
                ->color($pendingDonations > 0 ? 'warning' : 'gray')
                ->url('/admin/donations'),
            Stat::make('Total Raised', 'INR ' . number_format((float) $totalRaised, 2))
                ->icon('heroicon-o-heart')
                ->color('success')
                ->url('/admin/donations'),
        ];
    }
}
