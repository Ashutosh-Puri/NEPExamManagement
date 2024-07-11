<div>
  <section>
    <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Exam
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="exam_name" :value="__('Exam Name')" />
          <x-required />
          <x-text-input id="exam_name" type="text" wire:model="exam_name" name="exam_name" class="w-full mt-1" :value="old('exam_name', $exam_name)" required autofocus autocomplete="exam_name" />
          <x-input-error :messages="$errors->get('exam_name')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="academicyear_id" :value="__('Academic Year')" />
          <x-required />
          <x-input-select id="academicyear_id" wire:model="academicyear_id" name="academicyear_id" class="text-center w-full mt-1" :value="old('academicyear_id', $academicyear_id)" required autofocus autocomplete="academicyear_id">
            <x-select-option class="text-start" hidden> -- Select Academic Year -- </x-select-option>
            @foreach ($academics as $a_id =>$a_name)
              <x-select-option wire:key="{{ $a_id }}" value="{{ $a_id }}" class="text-start">{{ $a_name }}</x-select-option>
            @endforeach
          </x-input-select>
          <x-input-error :messages="$errors->get('academicyear_id')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="month" :value="__('Month')" />
          <x-required />
          <x-input-select id="month" wire:model="month" name="month" class="text-center w-full mt-1" :value="old('month', $month)" required autofocus autocomplete="month">
            <x-select-option class="text-start" hidden> -- Select Month -- </x-select-option>
            @foreach ($months as $monthid => $monthname)
              <x-select-option wire:key="{{ $monthid }}" value="{{  $monthname }}" class="text-start">{{  $monthname }}</x-select-option>
            @endforeach
          </x-input-select>
          <x-input-error :messages="$errors->get('month')" class="mt-2" />
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="exam_sessions" :value="__('Session')" />
          <x-required />
          <x-input-select id="exam_sessions" wire:model="exam_sessions" name="exam_sessions" class="text-center  w-full mt-1" :value="old('exam_sessions', $exam_sessions)" required autocomplete="exam_sessions">
            <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
            <x-select-option class="text-start" value="1">Session 1</x-select-option>
            <x-select-option class="text-start" value="2">Session 2</x-select-option>
          </x-input-select>
          <x-input-error :messages="$errors->get('exam_sessions')" class="mt-2" />
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
      <div class="h-20 p-2">
        <x-form-btn wire:target="college_logo_path" wire:loading.attr="disable">
          Submit
        </x-form-btn>
      </div>
    </div>
</div>
</div>
