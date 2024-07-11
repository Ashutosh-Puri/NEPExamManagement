<div>
    <section>
        <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
                Subject Credits
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="credit" :value="__('Credit')" />
                    <x-required />
                    <x-text-input id="credit" type="number" step="any" wire:model="credit" name="credit" class="w-full mt-1" :value="old('credit',$credit)" required autofocus autocomplete="credit" />
                    <x-input-error :messages="$errors->get('credit')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="marks" :value="__('Marks')" />
                    <x-required />
                    <x-text-input id="marks" type="number" step="any" wire:model="marks" name="marks" class="w-full mt-1" :value="old('marks',$marks)" required autofocus autocomplete="marks" />
                    <x-input-error :messages="$errors->get('marks')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="passing" :value="__(' Passing ')" />
                    <x-required />
                    <x-text-input id="passing" type="number" step="any" wire:model="passing" name="passing" class="w-full mt-1" :value="old('passing',$passing)" required autofocus autocomplete="passing" />
                    <x-input-error :messages="$errors->get('passing')" class="mt-2" />
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
