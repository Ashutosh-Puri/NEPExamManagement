<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Faculty Head Detail's
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="faculty_id" :value="__('Faculty Name')" />
            <x-input-select id="faculty_id" wire:model="faculty_id" name="faculty_id" class="text-center @error('faculty_id') is-invalid @enderror w-full mt-1" :value="old('faculty_id', $faculty_id)" required autofocus autocomplete="faculty_id">
                <x-select-option class="text-start" hidden> -- Select Faculty -- </x-select-option>
                @forelse ($faculties as $facultyid => $facultyname )
                    <x-select-option wire:key="{{ $facultyid }}" value="{{ $facultyid }}" class="text-start"> {{ $facultyname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Faculties Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('faculty_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="department_id" :value="__('Department')" />
            <x-input-select id="department_id" wire:model.live="department_id" name="department_id" class="text-center @error('department_id') is-invalid @enderror w-full mt-1" :value="old('department_id', $department_id)" required autofocus autocomplete="department_id">
                <x-select-option class="text-start" hidden> -- Select Department -- </x-select-option>
                @forelse ($departments as $departmentid => $departmentname )
                    <x-select-option wire:key="{{ $departmentid }}" value="{{ $departmentid }}" class="text-start"> {{ $departmentname }} </x-select-option>
                @empty
                    <x-select-option class="text-start">Departments Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
        </div>
    </div>
    <x-form-btn>Submit</x-form-btn>
  </div>
