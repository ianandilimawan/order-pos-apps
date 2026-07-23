<?php

namespace App\Livewire\Admin;

use App\Models\Promo;
use Livewire\Component;
use Livewire\WithPagination;

class PromoReport extends Component
{
    use WithPagination;

    public Promo $promo;
    public $dateRange = '';

    public function mount(Promo $promo)
    {
        $this->promo = $promo;
    }

    public function render()
    {
        $query = $this->promo->orders()->orderBy('created_at', 'desc');

        if (!empty($this->dateRange)) {
            $dates = explode(' to ', $this->dateRange);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [
                    \Carbon\Carbon::parse($dates[0])->startOfDay(),
                    \Carbon\Carbon::parse($dates[1])->endOfDay(),
                ]);
            } else {
                $query->whereDate('created_at', \Carbon\Carbon::parse($dates[0]));
            }
        }

        // Calculate summary
        $summaryQuery = clone $query;
        $totalUsage = $summaryQuery->count();
        $totalDiscount = $summaryQuery->sum('discount_amount');

        $orders = $query->paginate(10);

        return view('livewire.admin.promo-report', [
            'orders' => $orders,
            'totalUsage' => $totalUsage,
            'totalDiscount' => $totalDiscount,
        ]);
    }
}
