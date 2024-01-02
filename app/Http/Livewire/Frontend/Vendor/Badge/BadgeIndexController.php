<?php

namespace App\Http\Livewire\Frontend\Vendor\Badge;

use App\Http\Livewire\Traits\Notifies;
use App\Http\Livewire\Traits\ResetsPagination;
use App\Http\Livewire\Traits\WithBootstrapPagination;
use App\Models\Badge;
use App\Models\BadgeRequest;
use App\View\Components\Frontend\Layouts\SubMasterLayout;
use Closure;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BadgeIndexController extends Component
{
    use ResetsPagination,
        WithBootstrapPagination,
        WithPagination;
    use Notifies;

    public array $dateRange = [];

    public string $search = '';

    public string $sortBy = 'default';

    public function render(): View
    {
        return $this->view('frontend.vendor.badge.badge-index-controller', function (View $view) {
            $view->with('badges', $this->badges);
        });
    }

    public function getBadgesProperty(): Paginator
    {

        $badges = Badge::query()
            ->when($this->search, function ($query) {
                $query->whereHas('badges', function ($q) {
                    $q->where('name', 'LIKE', "%{$this->search}%")->orWhere('description', 'LIKE', "%{$this->search}%");
                });
            })
            ->when(!empty($this->dateRange), function ($query) {
                if (isset($this->dateRange[0])) {
                    $query->whereDate('created_at', '>=', $this->dateRange[0]);
                }
                if (isset($this->dateRange[1]) && $this->dateRange[1] != '...') {
                    $query->whereDate('created_at', '<=', $this->dateRange[1]);
                }
            });

        if ($this->sortBy == 'latest') {
            $badges->latest('created_at');
        }

        if ($this->sortBy == 'oldest') {
            $badges->oldest('created_at');
        }

        if ($this->sortBy == 'asc') {
            $badges->oldest('id');
        }

        if ($this->sortBy == 'desc') {
            $badges->latest('id');
        }

        if ($this->sortBy == 'a_to_z') {
            $badges->orderBy('name', 'ASC');
        }

        if ($this->sortBy == 'z_to_a') {
            $badges->orderBy('name', 'DESC');
        }

        return $badges->latest('created_at')->paginate(10);
    }

    public function resetFields(): void
    {
        $this->search = '';
        $this->dateRange = [];
    }

    public function view(string $view, Closure $closure = null): View
    {
        return tap(view($view), $closure)
            ->layout(SubMasterLayout::class, [
                'menuName' => 'vendor',
                'unReadMessageCount' => 0,
            ]);
    }

    public function requestBadge(Badge $badge)
    {
        $badge_request = new BadgeRequest();
        $badge_request->user_id = Auth::Guard('web')->id();
        $badge_request->badge_id = $badge->id;
        $badge_request->save();
        $this->notify(
            __('badges.request.created'),
            'vendor.vendor-profile'
        );
    }
}
