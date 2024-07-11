<div>
    <div class="mx-auto max-w-7xl sm:px-6 lg:p-2">
        <section>
            <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="max_gp" :value="__('Max Grade Point')" />
                        <x-required />
                        <x-text-input id="max_gp" type="number" step="any" wire:model="max_gp" name="max_gp" class="w-full mt-1" :value="old('max_gp',$max_gp)" required autofocus autocomplete="max_gp" />
                        <x-input-error :messages="$errors->get('max_gp')" class="mt-2" />
                    </div>

                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="min_gp" :value="__('Min Grade Point')" />
                        <x-required />
                        <x-text-input id="min_gp" type="number" step="any" wire:model="min_gp" name="min_gp" class="w-full mt-1" :value="old('min_gp',$min_gp)" required autofocus autocomplete="min_gp" />
                        <x-input-error :messages="$errors->get('min_gp')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="grade" :value="__(' Grade ')" />
                        <x-required />
                        <x-text-input id="grade" type="text" wire:model="grade" name="grade" class="w-full mt-1" :value="old('grade',$grade)" required autofocus autocomplete="grade" />
                        <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                    </div>

                    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-input-label for="description" :value="__('Description')" />
                        <x-text-input id="description" type="text" wire:model="description" name="description" class="w-full mt-1" :value="old('description',$description)"  autofocus autocomplete="description" />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>
                <div class="h-20 p-2">
                    <x-form-btn>
                        Submit
                    </x-form-btn>
                </div>
            </div>
        </section>
    </div>
</div>
