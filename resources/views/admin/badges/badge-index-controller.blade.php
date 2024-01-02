<div>
    <div class="text-right mb-4">
        <x-admin.components.button tag="a" href="{{ route('admin.badges.create') }}">
            {{ __('badges.index.actions.create') }}
        </x-admin.components.button>
    </div>
    <div class="p-4 space-y-4">
        <div class="flex items-center space-x-4">
            <div class="grid grid-cols-12 w-full space-x-4">
                <div class="col-span-8 md:col-span-8">
                    <x-admin.components.input.text wire:model.debounce.300ms="search"
                                                   placeholder="{{ __('badges.index.search_placeholder') }}"/>
                </div>
                <div class="col-span-4 text-right md:col-span-4">
                    <div class="sort-item ">
                        <select name="sortBy"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                wire:model="sortBy">
                            <option value="default" selected="selected">{{ __('global.sorting.default') }}</option>
                            <option value="latest">{{ __('global.sorting.latest') }}</option>
                            <option value="oldest">{{ __('global.sorting.oldest') }}</option>
                            <option value="asc">{{ __('global.sorting.ascending') }}</option>
                            <option value="desc">{{ __('global.sorting.descending') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-admin.components.table class="w-full whitespace-no-wrap p-2">
        <x-slot name="head">
            <x-admin.components.table.heading>{{ __('global.name') }}</x-admin.components.table.heading>
            <x-admin.components.table.heading>{{ __('global.description') }}</x-admin.components.table.heading>
            <x-admin.components.table.heading>{{ __('global.image') }}</x-admin.components.table.heading>
            <x-admin.components.table.heading>{{ __('global.date') }}</x-admin.components.table.heading>
            <x-admin.components.table.heading></x-admin.components.table.heading>
        </x-slot>
        <x-slot name="body">
            @forelse($badges as $badge)
                <x-admin.components.table.row wire:loading.class.delay="opacity-50">
                    <x-admin.components.table.cell>{{ $badge->name }}</x-admin.components.table.cell>
                    <x-admin.components.table.cell>{{ $badge->description }}</x-admin.components.table.cell>
                    <x-admin.components.table.cell><img src="{{ $badge->image }}" style="width: 70px; height: 70px; border-radius: 100%" alt=""></x-admin.components.table.cell>
                    <x-admin.components.table.cell>{{ $badge->created_at->format('m/d/Y') }}</x-admin.components.table.cell>
                    <x-admin.components.table.cell>
                        <a href="{{ route('admin.badges.show', $badge->id) }}" class="text-indigo-500 hover:underline">
                            {{ __('badges.edit') }}
                        </a>
                    </x-admin.components.table.cell>
                </x-admin.components.table.row>
            @empty
                <x-admin.components.table.no-results/>
            @endforelse
        </x-slot>
    </x-admin.components.table>
    <div>
        {{ $badges->links() }}
    </div>
</div>
