<?php

namespace App\Livewire\User\Pages;

use App\Models\CashoutRequest;
use App\Models\Offer;
use App\Models\Provider;
use App\Models\User;
use Date;
use Livewire\Component;

class HomePage extends Component
{
    public int $availableOffersCount = 0;

    public function render()
    {
        if (auth()->check()) {
            redirect()->route('earn');
        }

        $offerPartners = Provider::offer()->active()->ordered()->get();
        $surveyPartners = Provider::survey()->active()->ordered()->get();

        return view('livewire.user.pages.home-page')->with([
            'offerPartners' => $offerPartners,
            'surveyPartners' => $surveyPartners,
            'statistics' => $this->statistics(),
            'faqsInfo' => FaqPage::faqs(),
        ])->layout('layouts.app');
    }

    public function loadAvailableOffersCount(): void
    {
        $this->availableOffersCount = Offer::whereJsonContains('countries', strtoupper(country_code()))->count();
    }

    protected function statistics(): array
    {
        $userLatestCashoutTimeAvg = CashoutRequest::where('status', '!=', 'pending')
            ->whereNotNull('updated_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_minutes')
            ->value('avg_minutes');


        $totalEarnedYesterday = User::query()
            ->join('leads', 'users.id', '=', 'leads.user_id')
//            ->where('leads.status', 'approved')
            ->whereDate('leads.created_at', Date::yesterday())
            ->sum('leads.points');

        $totalEarnedYesterday = number_format(to_money($totalEarnedYesterday), 2);
        $totalEarnedYesterdayAvg = User::query()
            ->join('leads', 'users.id', '=', 'leads.user_id')
//            ->where('leads.status', 'approved')
            ->whereDate('leads.created_at', Date::yesterday())
            ->avg('leads.points');

        $totalEarnedYesterdayAvg = number_format(to_money($totalEarnedYesterdayAvg), 2);


        $totalEarned = User::query()
            ->join('leads', 'users.id', '=', 'leads.user_id')
//            ->where('leads.status', 'approved')
            ->sum('leads.points');

        $totalEarned = number_format(to_money($totalEarned), 2);


        return [
            'totalEarnedYesterday' => $totalEarnedYesterday,
            'totalEarnedYesterdayAvg' => $totalEarnedYesterdayAvg,
            'totalEarned' => $totalEarned,
            'userLatestCashoutTimeAvg' => $userLatestCashoutTimeAvg ? gmdate('H\h i\m', $userLatestCashoutTimeAvg * 60) : '00h 00m',
        ];
    }

}
