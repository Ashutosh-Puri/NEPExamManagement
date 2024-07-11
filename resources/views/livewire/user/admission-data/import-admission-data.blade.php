<div>
  <div wire:loading wire:target="perPage , search , nextPage , previousPage ,gotoPage ,import , export " role="status" class="absolute -translate-x-1/2 -translate-y-1/2 top-2/4 left-1/2">
    <svg aria-hidden="true" class="w-24 h-24 text-gray-300 animate-spin dark:text-gray-600 fill-primary" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
    <span class="sr-only">Loading...</span>
  </div>
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Import Admission Data <x-spinner />
    </div>
    <div class="grid grid-cols-1 md:grid-cols-12 py-2 ">
      <div class="col-span-3">
        <div class="grid grid-cols-12">
          <div class="px-2 py-2 col-span-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="patternclass_id" :value="__('Reset')" /><br>
            <x-table.cancel wire:click='inputreset()' class="!float-start mt-1 h-8  inline-flex" />
          </div>
          <div class="px-2 col-span-10 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="patternclass_id" :value="__('Select Class')" />
            <x-input-select id="patternclass_id" wire:model.live="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
              <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
              @forelse ($patternclasses as $pattern_calss)
                <x-select-option wire:key="{{ $pattern_calss->id }}" value="{{ $pattern_calss->id }}" class="text-start">  {{ $pattern_calss->classyear_name ?? '-' }} {{ $pattern_calss->course_name ?? '-' }} {{ $pattern_calss->pattern_name ?? '-' }}</x-select-option>
              @empty
                <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
              @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
          </div>
        </div>
      </div>
      <div class="px-2 col-span-3 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="subjectvertical_id" :value="__('Select Subject Vertical')" />
        <x-input-select id="subjectvertical_id" wire:model.live="subjectvertical_id" name="subjectvertical_id" class="text-center w-full mt-1" :value="old('subjectvertical_id', $subjectvertical_id)" required autocomplete="subjectvertical_id">
          <x-select-option class="text-start" hidden> -- Select Subject Vertical -- </x-select-option>
          @forelse ($subject_verticals as $subject_vertical_id => $subject_vertical_name)
            <x-select-option wire:key="{{ $subject_vertical_id }}" value="{{ $subject_vertical_id }}" class="text-start"> {{ $subject_vertical_name ?? '-' }} </x-select-option>
          @empty
            <x-select-option class="text-start">Subject Verticals Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('subjectvertical_id')" class="mt-1" />
      </div>
      <div class="px-2 col-span-3 py-2  text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="subject_id" :value="__('Select Subject')" />
        <x-input-select id="subject_id" wire:model="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" required autocomplete="subject_id">
          <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
          @forelse ($subjects as $subjectid => $subjectname)
            <x-select-option wire:key="{{ $subjectid }}" value="{{ $subjectid }}" class="text-start"> {{ $subjectname }} </x-select-option>
          @empty
            <x-select-option class="text-start">Subjects Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
      </div>
      <div class="px-2 col-span-3 py-2 text-sm text-gray-600 dark:text-gray-400 relative">
        <x-input-label for="subject_id" :value="__('Choose File')" />
        <div class="m-1 w-full inline-flex">
          <form class="w-full inline-flex" wire:submit="import()">
            <input accept=".xlsx, .csv" type="file" wire:model="importfile" class="w-full bg-white block flex-auto rounded-l h-10 text-sm file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-semibold dark:file:bg-primary file:text-white dark:hover:file:bg-primary-darker hover:file:bg-primary-darker file:bg-primary border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:border-primary-darker border " />
            <button type="submit" wire:loading.remove wire:target='import()' class="cursor-pointer hover:bg-primary-600 focus:bg-primary-600 active:bg-primary-700 inline-block h-10 rounded-r bg-primary px-3 pb-2 pt-2.5 text-xs font-medium leading-normal text-white  transition duration-150 ease-in-out " id="button-addon2">
              <span>Import</span>
            </button>
            <button type="button" wire:loading.flex wire:target='import()' class="cursor-not-allowed hover:bg-primary-600 focus:bg-primary-600 active:bg-primary-700 inline-block h-10 rounded-r bg-primary px-3 pb-2 pt-2.5 text-xs font-medium leading-normal text-white " id="button-addon2">
              <span>Import</span>
            </button>
          </form>
        </div>
        @error('importfile')
          <span class="absolute block left-5 bottom--5 mb-2 text-red-500 text-xs">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>
</div>
