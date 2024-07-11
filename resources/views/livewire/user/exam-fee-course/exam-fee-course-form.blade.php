<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Exam Fee Course
  </div>
  <div class="grid grid-cols-1 @if ($is_course_class || $is_sem || $mode=='edit' || $mode=='add') md:grid-cols-2 @endif @if ($is_sem && $mode=='edit')  md:grid-cols-3 @endif ">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="form_type_id" :value="__('Select Form Type')" />
      <x-input-select id="form_type_id" wire:model.live="form_type_id" name="form_type_id" class="text-center w-full mt-1" :value="old('form_type_id', $form_type_id)" required autocomplete="form_type_id">
        <x-select-option class="text-start" hidden> -- Select Form Types -- </x-select-option>
        @forelse ($formtypes as $formtypeid => $formtypename)
          <x-select-option wire:key="{{ $formtypeid }}" value="{{ $formtypeid }}" class="text-start"> {{ $formtypename?? '-' }}</x-select-option>
        @empty
          <x-select-option class="text-start">Form Types Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('form_type_id')" class="mt-1" />
    </div>
    @if ($mode == 'add')
      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="apply_fee_id" :value="__('Select Apply Fee Type')" />
        <x-input-select id="apply_fee_id" wire:model.live="apply_fee_id" name="apply_fee_id" class="text-center w-full mt-1" :value="old('apply_fee_id', $apply_fee_id)" required autocomplete="apply_fee_id">
          <x-select-option class="text-start" hidden> -- Select Apply Fee Type -- </x-select-option>
          @forelse ($applyfees as $applyfeeid => $applyfeename)
            <x-select-option wire:key="{{ $applyfeeid }}" value="{{ $applyfeeid }}" class="text-start"> {{ $applyfeename ?? '-' }}</x-select-option>
          @empty
            <x-select-option class="text-start">Apply Fee Types Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('apply_fee_id')" class="mt-1" />
      </div>

    @endif
    @if ($mode == 'add')
      @if ($is_course)
        <div class="px-5  py-2 text-sm text-gray-600 dark:text-gray-400 @if ($is_course_class==0) col-span-2  @endif">
          <x-input-label for="course_id" :value="__('Select Course')" />
          <x-input-select id="course_id" wire:model.live="course_id" name="course_id" class="text-center w-full mt-1" :value="old('course_id', $course_id)" required autocomplete="course_id">
            <x-select-option class="text-start" hidden> -- Select Course -- </x-select-option>
            @forelse ($courses as $courseid => $coursename)
              <x-select-option wire:key="{{ $courseid }}" value="{{ $courseid }}" class="text-start"> {{  $coursename ?? '-' }} </x-select-option>
            @empty
              <x-select-option class="text-start">Courses Not Found</x-select-option>
            @endforelse
          </x-input-select>
          <x-input-error :messages="$errors->get('course_id')" class="mt-1" />
        </div>
      @endif
      @if ($is_course_class)
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="course_class_id" :value="__('Select Course Class')" />
          <x-input-select id="course_class_id" wire:model.live="course_class_id" name="course_class_id" class="text-center w-full mt-1" :value="old('course_class_id', $course_class_id)" required autocomplete="course_class_id">
            <x-select-option class="text-start" hidden> -- Select Course Class -- </x-select-option>
            @forelse ($courseclasses as $course_class)
              <x-select-option wire:key="{{ $course_class->id }}" value="{{ $course_class->id }}" class="text-start"> {{ $course_class->classyear->classyear_name ?? '-' }} {{ $course_class->course->course_name ?? '-' }} </x-select-option>
            @empty
              <x-select-option class="text-start">Course Classes Not Found</x-select-option>
            @endforelse
          </x-input-select>
          <x-input-error :messages="$errors->get('course_class_id')" class="mt-1" />
        </div>
      @endif
    @endif
    @if ($is_ptrn_class)
      <div class="px-5  py-2 text-sm text-gray-600 dark:text-gray-400">
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
    @endif
    @if ($is_sem)
      <div class="px-5  py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="sem" :value="__('Select Semester')" />
        <x-input-select id="sem" wire:model.live="sem" name="sem" class="text-center w-full mt-1" :value="old('sem', $sem)" required autocomplete="sem">
          <x-select-option class="text-start" hidden> -- Select Semester -- </x-select-option>
          @forelse ($semesters as $semesterid => $semester)
            <x-select-option wire:key="{{ $semesterid }}" value="{{ $semester}}" class="text-start"> {{ $semester ?? '-' }} </x-select-option>
          @empty
            <x-select-option class="text-start"> Semesters Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('sem')" class="mt-1" />
      </div>
    @endif
  </div>
  @forelse ($examfees as $exam_fee)
    <div class="grid grid-cols-1 md:grid-cols-6">
      <div class="px-5 col-span-2 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-show :value="$exam_fee->fee_name" />
      </div>
      <div class="px-5 col-span-2 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-text-input id="fees.{{ $exam_fee->id }}" type="number" name="fees.{{ $exam_fee->id }}" wire:model="fees.{{ $exam_fee->id }}" placeholder="{{ __('Enter ' . $exam_fee->fee_name . ' Fee') }}" class="w-full mt-1" :value="old('fees.{{ $exam_fee->id }}')" />
        @error("fees.{$exam_fee->id}")
          <div class="text-sm text-red-600 dark:text-red-400 space-y-1">{{ $message }}</div>
        @enderror
      </div>
      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-checkbox id="active_status.{{ $exam_fee->id }}" wire:model="active_status.{{ $exam_fee->id }}" name="active_status.{{ $exam_fee->id }}" :value="old('active_status.{{ $exam_fee->id }}')" />
        <x-input-label for="active_status.{{ $exam_fee->id }}" class="inline mb-1 mx-2" :value="__('Make In Active')" />
        @error("active_status.{$exam_fee->id}")
          <div class="text-sm text-red-600 dark:text-red-400 space-y-1">{{ $message }}</div>
        @enderror
      </div>
    </div>
  @empty
    <span class="flex items-center justify-center text-center mx-auto">Exam course fees not found or all fees are assigned to the current pattern class and semester.</span>
  @endforelse

  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
