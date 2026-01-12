<?php

namespace App\Livewire\User;

use App\Models\Offer;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class Offers extends Component
{
    use WithPagination;

    public bool $ready = false;
    public bool $swiper = false;
    public ?int $limit = null;

    #[Reactive]
    public int $sort = 1;
    #[Reactive]
    public string $search = '';
    #[Reactive]
    public string $provider = '';
    #[Reactive]
    public array $devices = [];

    public string $category = '';

    public $selectedOffer;


    public function render()
    {
        return view('livewire.user.offers', [
            'offers' => $this->ready ? $this->offers() : collect(),
        ]);
    }

    private function offers()
    {
        $country = strtoupper(country_code() ?? 'UNKNOWN');
        return Offer::query()
            ->whereDoesntHave('leads', function ($query) {
                $query->where('ip', '=', ip());
            })
            ->withCount('leads')
            ->where(function ($query) use ($country) {
                return $query->whereJsonContains('countries', $country)
                    ->orWhereJsonLength('countries', 0);
            })->when($this->search, function ($query) {
                return $query->where('title', 'like', '%' . $this->search . '%');
            })->when($this->sort == 3, function ($query) {
                return $query->orderBy('leads_count', 'desc');
            })->when($this->sort == 1, function ($query) {
                return $query->orderBy('points', 'desc');
            })->when($this->sort == 2, function ($query) {
                return $query->orderBy('points', 'asc');
            })->when($this->provider, function ($query) {
                return $query->where('provider', $this->provider);
            })->when($this->category, function ($query) {
                return $query->whereJsonContains('categories', $this->category);
            })->when(count($this->devices), function ($query) {
                return $query->where(function ($query) {
                    foreach ($this->devices as $device) {
                        $query->orWhereJsonContains('devices', $device);
                    }
                });
            })->paginate($this->limit ?? 63);
    }
}
