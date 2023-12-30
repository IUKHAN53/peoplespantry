<div class="overflow-hidden shadow-gray-800 dark:shadow-gray-50 border border-gray-300 dark:border-gray-500 sm:rounded-lg">
    <div class="p-4 space-y-4">
        <div class="flex items-center space-x-4">
            <div class="grid grid-cols-12 w-full space-x-4">
                <div class="col-span-8 md:col-span-8">
                    {{ __('reports.top_ten_products') }}
                </div>
            </div>
        </div>
    </div>
    <x-admin.components.table class="w-half whitespace-no-wrap p-2">
        <x-slot name="head">
            <x-admin.components.table.heading>{{ __('reports.top_ten_products.name') }}</x-admin.components.table.heading>
            <x-admin.components.table.heading>{{ __('reports.top_ten_products.revenue') }}</x-admin.components.table.heading>
        </x-slot>
        <x-slot name="body">
            @forelse($topTenProducts as $product)
                <x-admin.components.table.row wire:loading.class.delay="opacity-50">
                    <x-admin.components.table.cell>{{ $product->title }}</x-admin.components.table.cell>
                    <x-admin.components.table.cell>${{ $product->total_price }}</x-admin.components.table.cell>
                </x-admin.components.table.row>
            @empty
                <x-admin.components.table.no-results />
            @endforelse
        </x-slot>
    </x-admin.components.table>
</div>