<div class="col-span-12 space-y-4">
    <x-admin.components.card heading="Badge Information">
        <div class="grid grid-cols-2 gap-4">
            <x-admin.components.input.group label="{{ __('inputs.name') }}" for="name"
                                            :error="$errors->first('badge.name')">
                <x-admin.components.input.text wire:model.defer="badge.name" name="name" id="name"
                                               :error="$errors->first('badge.name')"/>
            </x-admin.components.input.group>
            <x-admin.components.input.group label="{{ __('inputs.description') }}" for="description"
                                            :error="$errors->first('badge.description')">
                <x-admin.components.input.text wire:model.defer="badge.description" name="description" id="description"
                                               :error="$errors->first('badge.description')"/>
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
            @endif
            @error('image')
            <div class="error">
                <p class="text-sm text-red-600"> {{ $message }} </p>
            </div>
            @enderror
        </div>
    </x-admin.components.card>
    <div class="px-4 py-3 text-right rounded shadow bg-gray-50 sm:px-6">
        <button wire:loading.attr="disabled" wire:target="submit" type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __($badge->id ? 'badges.form.update_btn' : 'badges.form.create_btn') }}
        </button>
    </div>
</div>
