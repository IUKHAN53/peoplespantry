<div x-data>
    <x-admin.components.slideover title="Update Badge Request">
        <div class="space-y-4" x-data="{
            id: null,
            tab: 'search',
            showRejectReasonField: false,
        }" @showslideover.window="id=$event.detail">
            <div>
                <nav class="flex space-x-4" aria-label="Tabs">
                    <button @click.prevent="$wire.approveBadgeRequest(id)" class="px-3 py-2 text-sm font-medium rounded-md" :class="{
                        'bg-indigo-100 text-indigo-700': tab == 'search',
                        'text-gray-500 hover:text-gray-700': tab != 'search'
                    }">
                        Approved
                    </button>

                    <button @click="showRejectReasonField = true" class="px-3 p~y-2 text-sm font-medium rounded-md" :class="{
                        'bg-indigo-100 text-indigo-700': tab == 'search',
                        'text-gray-500 hover:text-gray-700': tab != 'search'
                    }">
                        Reject
                    </button>

                </nav>
                <br>
                <div>
                    <div x-show="showRejectReasonField" @click.away="showRejectReasonField=false">
                        <x-admin.components.input.text wire:model.defer="comments" type="text" placeholder="Enter additional comments" :error="$errors->first('comments')" />
                        @error('rejected_reason')
                            <div class="space-y-1 text-center">
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            </div>
                        @enderror
                        <br>
                        <button @click.prevent="$wire.rejectBadgeRequest(id)" class="px-3 py-2 text-sm font-medium rounded-md" :class="{
                            'bg-indigo-100 text-indigo-700': tab == 'search',
                            'text-gray-500 hover:text-gray-700': tab != 'search'
                        }">
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="content">
            <x-admin.components.loading wire:loading />
        </x-slot>

    </x-admin.components.slideover>
</div>
