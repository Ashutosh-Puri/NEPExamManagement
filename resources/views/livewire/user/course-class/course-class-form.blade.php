<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Course
  </div>
  <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
    <x-input-label for="college_id" :value="__('Select College')" />
    <x-input-select id="college_id" wire:model="college_id" name="college_id" class="text-center w-full mt-1" :value="old('college_id', $college_id)" required autocomplete="college_id">
      <x-select-option class="text-start" hidden> -- Select College -- </x-select-option>
      @forelse ($colleges as $collegeid => $collegename)
        <x-select-option wire:key="{{ $collegeid }}" value="{{ $collegeid }}" class="text-start"> {{ $collegename }} </x-select-option>
      @empty
        <x-select-option class="text-start">Colleges Not Found</x-select-option>
      @endforelse
    </x-input-select>
    <x-input-error :messages="$errors->get('college_id')" class="mt-1" />
  </div>
  <div class="grid grid-cols-1 md:grid-cols-3">
    <div class="px-5 py-2   text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="classyear_id" :value="__('Select Class Year')" />
      <x-input-select id="classyear_id" wire:model="classyear_id" name="classyear_id" class="text-center w-full mt-1" :value="old('classyear_id', $classyear_id )" required autocomplete="classyear_id">
        <x-select-option class="text-start" hidden> -- Select Class Year -- </x-select-option>
        @forelse ($class_years as $class_yearid => $class_yearname)
          <x-select-option wire:key="{{  $class_yearid }}" value="{{ $class_yearid }}" class="text-start"> {{ $class_yearname }} </x-select-option>
        @empty
          <x-select-option class="text-start">Class Years Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('classyear_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="course_id" :value="__('Select Course')" />
      <x-input-select id="course_id" wire:model.live="course_id" name="course_id" class="text-center w-full mt-1" :value="old('course_id', $course_id)" required autocomplete="course_id">
        <x-select-option class="text-start" hidden> -- Select Course -- </x-select-option>
        @forelse ($courses as $courseid => $coursename)
          <x-select-option wire:key="{{ $courseid }}" value="{{ $courseid }}" class="text-start"> {{ $coursename }} </x-select-option>
        @empty
          <x-select-option class="text-start">Courses Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('course_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="nextyearclass_id" :value="__('Select Next Class')" />
      <x-input-select id="nextyearclass_id" wire:model="nextyearclass_id" name="nextyearclass_id" class="text-center w-full mt-1" :value="old('nextyearclass_id',$nextyearclass_id )"  autocomplete="nextyearclass_id">
        <x-select-option class="text-start" hidden> -- Select Next Class -- </x-select-option>
        @forelse ($next_classess as $next_class)
          <x-select-option wire:key="{{ $next_class->id }}" value="{{ $next_class->id }}" class="text-start">{{  $next_class->classyear->classyear_name??'-' }}  {{ $next_class->course->course_name??'-' }} </x-select-option>
        @empty
          <x-select-option class="text-start">Next Classes Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('nextyearclass_id')" class="mt-1" />
    </div>
    
  </div>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
