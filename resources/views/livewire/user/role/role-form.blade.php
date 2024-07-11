<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Role Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="role_name" :value="__('Role Name')" />
            <x-text-input id="role_name" type="text" wire:model="role_name" name="role_name" placeholder="Role Name" class=" @error('role_name') is-invalid @enderror w-full mt-1" :value="old('role_name', $role_name)" required autofocus autocomplete="role_name" />
            <x-input-error :messages="$errors->get('role_name')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="roletype_id" :value="__('Role Type')" />
            <x-input-select id="roletype_id" wire:model="roletype_id" name="roletype_id" class="text-center @error('roletype_id') is-invalid @enderror w-full mt-1" :value="old('roletype_id', $roletype_id)" required autofocus autocomplete="roletype_id">
                <x-select-option class="text-start" hidden> -- Select Role Type -- </x-select-option>
                @forelse ($roletypes as $roletypeid => $roletypename )
                    <x-select-option wire:key="{{ $roletypeid }}" value="{{ $roletypeid }}" class="text-start">{{ $roletypename }}</x-select-option>
                @empty
                    <x-select-option class="text-start">Role Types Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('roletype_id')" class="mt-2" />
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-1">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="college_id" :value="__('Role')" />
            <x-input-select id="college_id" wire:model.live="college_id" name="college_id" class="text-center @error('college_id') is-invalid @enderror w-full mt-1" :value="old('college_id', $college_id)" required autofocus autocomplete="college_id">
                <x-select-option class="text-start" hidden> -- Select College -- </x-select-option>
                @forelse ($colleges as $collegeid => $collegename )
                    <x-select-option wire:key="{{ $collegeid }}" value="{{ $collegeid }}" class="text-start">{{ $collegename }}</x-select-option>
                @empty
                    <x-select-option class="text-start">Colleges Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('college_id')" class="mt-2" />
        </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
</div>
