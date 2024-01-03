<?php

namespace App\Http\Livewire\Admin\Badges;

use App\Http\Livewire\Traits\Notifies;
use App\Models\Badge;
use App\Models\BadgeRequest;
use App\View\Components\Admin\Layouts\MasterLayout;
use Closure;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class BadgeShowController extends Component
{
    use Notifies;
    use WithFileUploads;

    public Badge $badge;

    public string $comments;

    public $image;

    public $image_link;

    public function mount(Badge $badge): void
    {
        $this->badge = $badge;
        $this->image_link = $badge->image;
    }

    protected function rules()
    {
        return [
            'badge.name' => 'required',
            'badge.shape' => 'required',
            'badge.description' => 'required',
            'image' => ($this->image_link) ? '' : 'image|required',
        ];
    }

    public function render(): View
    {
        return $this->view('admin.badges.badge-show-controller', function (View $view) {
            $view->with('badgeRequests', $this->getBadgeRequests());
        });
    }

    public function updateBadge(): void
    {
        $this->validate();

        $this->badge->save();

        if ($this->image instanceof TemporaryUploadedFile) {
            $this->badge->clearMediaCollection('badges');

            $this->badge->addMedia($this->image->getRealPath())
                ->toMediaCollection('badges');

        }

        $this->notify(trans('badges.messages.updated'), 'admin.badges.index');
    }

    public function getBadgeRequests(): Paginator
    {
        $query = BadgeRequest::query()->where('badge_id', $this->badge->id);

        return $query->paginate(5);
    }

    public function approveBadgeRequest(BadgeRequest $badgeRequest): void
    {
        try {
            $badgeRequest->approveRequest();
        } catch (Exception $ex) {
            $this->emit('alert-danger', $ex->getMessage());
        }

        $this->notify(trans('badges.messages.updated'), 'admin.badges.index');
    }

    public function rejectBadgeRequest(BadgeRequest $badgeRequest): void
    {
        $this->validate(
            ['comments' => 'required']
        );

        $badgeRequest->rejectWithComments($this->comments);

        $this->notify(trans('badges.messages.updated'), 'admin.badges.index');
    }

    public function view(string $view, ?Closure $closure = null): View
    {
        return tap(view($view), $closure)
            ->layout(MasterLayout::class, [
                'title' => 'Badges',
                'menuName' => 'badges',
            ]);
    }

    public function getImagePreviewProperty(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if ($this->image instanceof TemporaryUploadedFile) {
            return $this->image->temporaryUrl();
        }

        return $this->image;
    }

    public function removeImage(): void
    {
        if ($this->image instanceof TemporaryUploadedFile) {
            $this->image->delete();
        } else {
            $this->badge->clearMediaCollection('badges');
        }
        $this->image = null;
        $this->image_link = null;
    }
}
