<?php

namespace App\Http\Livewire\Admin\Badges;

use App\Http\Livewire\Traits\Notifies;
use App\Models\Badge;
use App\Services\Admin\PermissionProviderService;
use App\View\Components\Admin\Layouts\MasterLayout;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class BadgeCreateController extends Component
{
    use Notifies;
    use WithFileUploads;

    private string $badgePath = 'badges';

    public Badge $badge;

    public TemporaryUploadedFile|string|null $image = null;

    public function mount()
    {
        $this->badge = new Badge();
    }

    protected function rules()
    {
        return [
            'badge.name' => 'required',
            'badge.shape' => 'required',
            'badge.description' => 'required',
            'image' => is_string($this->image) ? '' : 'image|required',
        ];
    }

    public function render(PermissionProviderService $permissionService): View
    {
        return $this->view('admin.badges.badge-create-controller');
    }

    public function view(string $view, ?Closure $closure = null): View
    {
        return tap(view($view), $closure)
            ->layout(MasterLayout::class, [
                'title' => 'Create Badge',
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
        }

        $this->image = null;
    }

    public function submit()
    {
        $this->validate();

        if ($this->image instanceof TemporaryUploadedFile) {
            $this->badge->addMedia($this->image->getRealPath())
                ->toMediaCollection('badges');
        }

        $this->badge->save();

        $this->notify(trans('badges.messages.created'), 'admin.badges.index');
    }
}
