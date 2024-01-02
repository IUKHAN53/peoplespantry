<?php

namespace App\Http\Livewire\Admin\Badges;

use App\Models\Badge;
use App\View\Components\Admin\Layouts\MasterLayout;
use Closure;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BadgeIndexController extends Component
{
    public string $search = '';

    public string $sortBy = 'default';

    public int $perPage;

    public function mount(): void
    {
        $this->perPage = 10;
    }

    public function render(): View
    {
        return $this->view('admin.badges.badge-index-controller', function (View $view) {
            $view->with('badges', $this->getBadges());
        });
    }

    public function getBadges(): Paginator
    {
        $query = Badge::query();

        if ($this->search) {
            $query->search($this->search);
        }

        if ($this->sortBy == 'latest') {
            $query->latest('created_at');
        }

        if ($this->sortBy == 'oldest') {
            $query->oldest('created_at');
        }

        if ($this->sortBy == 'asc') {
            $query->orderBy('name');
        }

        if ($this->sortBy == 'desc') {
            $query->orderBy('name', 'desc');
        }

        return $query
            ->latest('created_at')
            ->paginate($this->perPage);
    }

    public function view(string $view, Closure $closure = null): View
    {
        return tap(view($view), $closure)
            ->layout(MasterLayout::class, [
                'title' => 'Badges',
                'menuName' => 'badges',
            ]);
    }

}
