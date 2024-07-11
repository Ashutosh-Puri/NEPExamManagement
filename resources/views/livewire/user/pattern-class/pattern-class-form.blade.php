<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Pattern Class
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="pattern_id" :value="__('Select Class')" />
      <x-input-select id="pattern_id" wire:model.live="pattern_id" name="pattern_id" class="text-center w-full mt-1" :value="old('pattern_id', $pattern_id)" required autocomplete="pattern_id">
        <x-select-option class="text-start" hidden> -- Select  Pattern -- </x-select-option>
        @forelse ($patterns as $patternid => $patternname)
          <x-select-option wire:key="{{ $patternid  }}" value="{{ $patternid  }}" class="text-start"> {{ $patternname }} </x-select-option>
        @empty
          <x-select-option class="text-start">Patterns Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('pattern_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="class_id" :value="__('Select Course Classes')" />
      <x-input-select id="class_id" wire:model.live="class_id" name="class_id" class="text-center w-full mt-1" :value="old('class_id', $class_id)" required autocomplete="class_id">
        <x-select-option class="text-start" hidden> -- Select Course Classes -- </x-select-option>
        @forelse ($course_classes as $course_class)
          <x-select-option wire:key="{{ $course_class->id }}" value="{{ $course_class->id }}" class="text-start">{{ $course_class->classyear->classyear_name??'-' }} {{ $course_class->course->course_name??'-' }} </x-select-option>
        @empty
          <x-select-option class="text-start">Course Classes Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('class_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="sem1_total_marks" :value="__('SEM 1 Total Marks')" />
      <x-text-input id="sem1_total_marks" type="number" wire:model="sem1_total_marks" placeholder="{{ __('Enter SEM 1 Total Marks') }}" name="sem1_total_marks" class="w-full mt-1" :value="old('sem1_total_marks', $sem1_total_marks)" autocomplete="sem1_total_marks" />
      <x-input-error :messages="$errors->get('sem1_total_marks')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="sem2_total_marks" :value="__('SEM 2 Total Marks')" />
      <x-text-input id="sem2_total_marks" type="number" wire:model="sem2_total_marks" placeholder="{{ __('Enter SEM 2 Total Marks') }}" name="sem2_total_marks" class="w-full mt-1" :value="old('sem2_total_marks', $sem2_total_marks)" autocomplete="sem2_total_marks" />
      <x-input-error :messages="$errors->get('sem2_total_marks')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="sem1_credits" :value="__('SEM 1 Credits')" />
      <x-text-input id="sem1_credits" type="number" wire:model="sem1_credits" placeholder="{{ __('Enter SEM 1 Credits') }}" name="sem1_credits" class="w-full mt-1" :value="old('sem1_credits', $sem1_credits)" autocomplete="sem1_credits" />
      <x-input-error :messages="$errors->get('sem1_credits')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="sem2_credits" :value="__('SEM 2 Credits')" />
      <x-text-input id="sem2_credits" type="number" wire:model="sem2_credits" placeholder="{{ __('Enter SEM 2 Credits') }}" name="sem2_credits" class="w-full mt-1" :value="old('sem2_credits', $sem2_credits)" autocomplete="sem2_credits" />
      <x-input-error :messages="$errors->get('sem2_credits')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="credit" :value="__('Credit')" />
      <x-text-input id="credit" type="number" wire:model="credit" placeholder="{{ __('Enter SEM 2 Credits') }}" name="credit" class="w-full mt-1" :value="old('credit', $credit)" autocomplete="credit" />
      <x-input-error :messages="$errors->get('credit')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="sem1_totalnosubjects" :value="__('SEM 1 Total Subjects')" />
      <x-text-input id="sem1_totalnosubjects" type="number" wire:model="sem1_totalnosubjects" placeholder="{{ __('SEM 1 Total Subjects') }}" name="sem1_totalnosubjects" class="w-full mt-1" :value="old('sem1_totalnosubjects', $sem2_credits)" autocomplete="sem1_totalnosubjects" />
      <x-input-error :messages="$errors->get('sem1_totalnosubjects')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="sem2_totalnosubjects" :value="__('SEM 2 Total Subjects')" />
      <x-text-input id="sem2_totalnosubjects" type="number" wire:model="sem2_totalnosubjects" placeholder="{{ __('SEM 2 Total Subjects') }}" name="sem2_totalnosubjects" class="w-full mt-1" :value="old('sem2_totalnosubjects', $sem2_credits)" autocomplete="sem2_totalnosubjects" />
      <x-input-error :messages="$errors->get('sem2_totalnosubjects')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="status" :value="__('Status')" /> <br>
      <x-input-checkbox id="status"  wire:model="status"  name="status" :value="old('status',$status)" />
      <x-input-label for="status" class="inline mb-1 mx-2" :value="__('Make In Active')" />
      <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
  </div>

</div>
<x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
