<x-slot name="pageTitle">
    {{ __('badges.create.title') }}
</x-slot>
<form action="submit" method="POST" wire:submit.prevent="submit">
    <div class="grid grid-cols-12">
        @include('admin.badges.form')
    </div>
</form>
