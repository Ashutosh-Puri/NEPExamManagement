<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Admission Data Entry <x-spinner/>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="patternclass_id" :value="__('Select Class')" />
      <x-input-select id="patternclass_id" wire:model.live="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
        <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
        @forelse ($pattern_classes as $pattern_calss)
          <x-select-option wire:key="{{ $pattern_calss->id }}" value="{{ $pattern_calss->id }}" class="text-start"> {{ $pattern_calss->classyear_name ?? '-' }} {{ $pattern_calss->course_name ?? '-' }} {{ $pattern_calss->pattern_name ?? '-' }} </x-select-option>
        @empty
          <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="subject_id" :value="__('Select Subject')" />
      <x-input-select id="subject_id" wire:model="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" required autocomplete="subject_id">
        <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
        @forelse ($subjects as $subjectid => $subjectname)
          <x-select-option wire:key="{{ $subjectid }}" value="{{ $subjectid }}" class="text-start"> {{ $subjectname}} </x-select-option>
        @empty
          <x-select-option class="text-start">Subjects Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="memid" :value="__('Member ID')" />
      <x-text-input id="memid" type="number" wire:model="memid" placeholder="{{ __('Enter Member ID') }}" name="memid" class="w-full mt-1" :value="old('memid', $memid)" autocomplete="memid" />
      <x-input-error :messages="$errors->get('memid')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="stud_name" :value="__('Student Name')" />
      <x-text-input id="stud_name" type="text" wire:model="stud_name" placeholder="{{ __('Enter Student Name') }}" name="stud_name" class="w-full mt-1" :value="old('stud_name', $stud_name)" autocomplete="stud_name" />
      <x-input-error :messages="$errors->get('stud_name')" class="mt-1" />
    </div>
  </div>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
