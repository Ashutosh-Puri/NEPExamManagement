<div>
  <section>
    <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Department
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 col-span-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="college_id" :value="__('College')" />
          <x-required />
          <x-input-select id="college_id" wire:model="college_id" name="college_id" class="text-center w-full mt-1" :value="old('college_id', $college_id)" required autofocus autocomplete="college_id">
            <x-select-option class="text-start" hidden> -- Select College -- </x-select-option>
            @foreach ($colleges as $c_id => $cname)
              <x-select-option wire:key="{{ $c_id }}" value="{{ $c_id }}" class="text-start">{{ $cname }}</x-select-option>
            @endforeach
          </x-input-select>
          <x-input-error :messages="$errors->get('college_id')" class="mt-2" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="dept_name" :value="__('Department Name')" />
          <x-required />
          <x-text-input id="dept_name" type="text" wire:model="dept_name" name="dept_name" class="w-full mt-1" :value="old('dept_name', $dept_name)" required autofocus autocomplete="dept_name" />
          <x-input-error :messages="$errors->get('dept_name')" class="mt-2" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="short_name" :value="__('Short Name')" />
          <x-required />
          <x-text-input id="short_name" type="text" wire:model="short_name" name="short_name" class="w-full mt-1" :value="old('short_name', $short_name)" required autofocus autocomplete="short_name" />
          <x-input-error :messages="$errors->get('short_name')" class="mt-2" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="departmenttype_ids" :value="__('Select Department Type')" />
          <x-required />
          <x-input-select id="departmenttype_ids" wire:model="departmenttype_ids" name="departmenttype_ids" class="text-center  w-full mt-1" required multiple autocomplete="departmenttype_ids">
            <x-select-option class="text-start" hidden> -- Select Department Types -- </x-select-option>
            @foreach ($departmenttypes as $dept_id => $dept_name)
              <x-select-option wire:key="{{ $dept_id }}" value="{{ $dept_id }}" selected class="text-start">{{ $dept_name }}</x-select-option>
            @endforeach
          </x-input-select>
          <x-input-error :messages="$errors->get('departmenttype_ids')" class="mt-2" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="status" :value="__('Status')" />
          <x-required />
          <x-input-select id="status" wire:model="status" name="status" class="text-center  w-full mt-1" :value="old('status', $status)" required autocomplete="status">
            <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
            <x-select-option class="text-start" value="0">Inactive</x-select-option>
            <x-select-option class="text-start" value="1">Active</x-select-option>
          </x-input-select>
          <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
      </div>
    </div>
    <div class="h-20 p-2">
      <x-form-btn>
        Submit
      </x-form-btn>
    </div>
  </section>
</div>
