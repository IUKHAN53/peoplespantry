<div class="flex flex-col w-full">
    <div class="grid gap-4">
        <form method="POST" wire:submit.prevent="updateBadge" class="flex justify-between flex-col">
            <x-admin.components.card heading="Badge Information">
                <div class="grid grid-cols-1 gap-4">
                    <x-admin.components.input.group label="{{ __('badges.form.name') }}" for="name"
                                                    :error="$errors->first('badges.name')">
                        <x-admin.components.input.text wire:model.defer="badge.name" name="name"
                                                       id="name" :error="$errors->first('badge.name')"/>
                    </x-admin.components.input.group>
                    <x-admin.components.input.group label="{{ __('badges.form.description') }}" for="description"
                                                    :error="$errors->first('badge.description')">
                        <x-admin.components.input.text wire:model.defer="badge.description" name="description"
                                                       id="description" :error="$errors->first('badge.description')"/>
                    </x-admin.components.input.group>
                </div>
            </x-admin.components.card>
            <x-admin.components.card heading="Badge Image">
                <div>
                    <h4 class="pb-2">{{ __('badges.image.label') }}</h4>
                    <div x-data="{
                                image: @entangle('image')
                            }" x-show="!image">
                        <x-fileupload label="<span class='plus'>+</span>" :imagesHolder="null" wire:model="image"
                                      :filetypes="['image/*']" :multiple="false"/>
                    </div>

                    @if ($image)
                        <div class="feature-upload relative flex-wrap d-flex flex-row rounded border p-2">
                            <div class="preview-img">
                                <img class="img-fluid d-block mx-auto h-[150px]" src="{{ $this->imagePreview }}" alt="">
                            </div>
                            <button wire:loading.attr="disabled"
                                    class="inline-flex absolute top-2 right-2 justify-center items-center w-6 h-6 text-xs opacity-80 font-bold text-white bg-gray-700 rounded-full cursor-pointer"
                                    wire:target="removeImage" wire:click.prevent="removeImage()">
                                x
                            </button>
                        </div>
                    @elseif($image_link)
                        <div class="feature-upload relative flex-wrap d-flex flex-row rounded border p-2">
                            <div class="preview-img">
                                <img class="img-fluid d-block mx-auto h-[150px]" src="{{ $badge->image }}" alt="">
                            </div>
                            <button wire:loading.attr="disabled"
                                    class="inline-flex absolute top-2 right-2 justify-center items-center w-6 h-6 text-xs opacity-80 font-bold text-white bg-gray-700 rounded-full cursor-pointer"
                                    wire:target="removeImage" wire:click.prevent="removeImage()">
                                x
                            </button>
                        </div>
                    @endif
                    @error('image')
                    <div class="error">
                        <p class="text-sm text-red-600"> {{ $message }} </p>
                    </div>
                    @enderror
                </div>
            </x-admin.components.card>
            <div class="px-4 py-3 text-right rounded sm:px-6 mt-3">
                <button type="submit"
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('badges.buttons.update') }}
                </button>
            </div>
        </form>
    </div>
    <div
        class="overflow-hidden shadow-gray-800 dark:shadow-gray-50 border border-gray-300 dark:border-gray-500 sm:rounded-lg mt-10">
        <div class="p-4 space-y-4">
            <div class="flex items-center space-x-4">
                <div class="grid grid-cols-12 w-full space-x-4">
                    <div class="col-span-8 md:col-span-8">
                        <header>Badge Requests</header>
                    </div>
                </div>
            </div>
        </div>
        <x-admin.components.table class="w-full whitespace-no-wrap p-2">
            <x-slot name="head">
                <x-admin.components.table.heading>
                    {{ __('global.sr_no') }}
                </x-admin.components.table.heading>
                <x-admin.components.table.heading>
                    Requested By
                </x-admin.components.table.heading>
                <x-admin.components.table.heading>
                    {{ __('global.status') }}
                </x-admin.components.table.heading>
                <x-admin.components.table.heading>
                    {{ __('global.date') }}
                </x-admin.components.table.heading>
                <x-admin.components.table.heading>
                    Action
                </x-admin.components.table.heading>
            </x-slot>
            <x-slot name="body">
                @forelse ($badgeRequests as $badgeRequest)
                    <x-admin.components.table.row wire:loading.class.delay="opacity-50">
                        <x-admin.components.table.cell>
                            {{ $loop->iteration }}
                        </x-admin.components.table.cell>
                        <x-admin.components.table.cell>
                            <a href="{{route('admin.customer.show',$badgeRequest->user_id)}}">{{ $badgeRequest->user->name }}</a>
                        </x-admin.components.table.cell>
                        <x-admin.components.table.cell>
                            <span
                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                {{ $badgeRequest->status }}
                            </span>
                        </x-admin.components.table.cell>
                        <x-admin.components.table.cell>
                            {{ $badgeRequest->created_at->format('m/d/Y') }}
                        </x-admin.components.table.cell>
                        <x-admin.components.table.cell>
                            @if ($badgeRequest->status == 'pending')
                                <x-admin.components.button wire:loading type="button"
                                                           wire:key="badge_request_{{ $badgeRequest->id }}"
                                                           @click="$dispatch('showslideover', {{ $badgeRequest->id }})">
                                    Update Request
                                    @include('admin.layouts.livewire.button-loading')
                                </x-admin.components.button>
                            @endif
                        </x-admin.components.table.cell>

                    </x-admin.components.table.row>
                @empty
                    <x-admin.components.table.row>
                        <x-admin.components.table.cell colspan="4">
                            <div class="flex justify-center items-center space-x-2">
                                <span class="font-medium py-8 text-gray-400 text-xl">{{ __('global.no_data') }}</span>
                            </div>
                        </x-admin.components.table.cell>
                    </x-admin.components.table.row>
                @endforelse
                    <x-badge-request-status-action/>
            </x-slot>
        </x-admin.components.table>
    </div>
</div>
