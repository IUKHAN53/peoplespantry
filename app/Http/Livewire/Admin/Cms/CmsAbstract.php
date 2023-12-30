<?php

namespace App\Http\Livewire\Admin\Cms;

use Closure;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Contracts\View\View;
use App\Http\Livewire\Traits\Notifies;
use App\Http\Livewire\Traits\Authenticated;
use App\View\Components\Admin\Layouts\SubMasterLayout;

abstract class CmsAbstract extends Component
{
    use Authenticated, Notifies, WithFileUploads;

    /**
     * @param  view-string  $view
     */
    public function view(string $view, Closure $closure = null): View
    {
        return tap(view($view), $closure)
            ->layout(SubMasterLayout::class, [
                'menuName' => 'cms',
            ]);
    }
}
