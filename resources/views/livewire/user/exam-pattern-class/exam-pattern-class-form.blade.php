<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Exam Pattern Class
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="exam_id" :value="__('Select Exam')" />
      <x-input-select id="exam_id" wire:model="exam_id" name="exam_id" class="text-center w-full mt-1" :value="old('exam_id', $exam_id)" required autocomplete="exam_id">
        <x-select-option class="text-start" hidden> -- Select Exam -- </x-select-option>
        @forelse ($exams as $examid => $examname)
          <x-select-option wire:key="{{  $examid }}" value="{{  $examid }}" class="text-start"> {{ $examname }} </x-select-option>
        @empty
          <x-select-option class="text-start">Exams Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('exam_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="patternclass_id" :value="__('Select Class')" />
      <x-input-select id="patternclass_id" wire:model="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
        <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
        @forelse ($pattern_classes as $pattern_calss)
          <x-select-option wire:key="{{ $pattern_calss->id }}" value="{{ $pattern_calss->id }}" class="text-start"> {{ $pattern_calss->classyear_name ?? '-' }} {{ $pattern_calss->course_name ?? '-' }}  {{ $pattern_calss->pattern_name ?? '-' }}</x-select-option>
        @empty
          <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
    </div>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-4">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="result_date" :value="__('Result Date')" />
      <x-text-input id="result_date" type="date" wire:model="result_date" placeholder="{{ __('Result Date') }}" name="result_date" class="w-full mt-1" :value="old('result_date', $result_date)" autocomplete="result_date" />
      <x-input-error :messages="$errors->get('result_date')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="start_date" :value="__('Start Date')" />
      <x-text-input id="start_date" type="date" wire:model="start_date" placeholder="{{ __('Start Date') }}" name="start_date" class="w-full mt-1" :value="old('start_date', $start_date)" autocomplete="start_date" />
      <x-input-error :messages="$errors->get('start_date')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="end_date" :value="__('End Date')" />
      <x-text-input id="end_date" type="date" wire:model="end_date" placeholder="{{ __('End Date') }}" name="end_date" class="w-full mt-1" :value="old('end_date', $end_date)" autocomplete="end_date" />
      <x-input-error :messages="$errors->get('end_date')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="latefee_date" :value="__('Late Fee Date')" />
      <x-text-input id="latefee_date" type="date" wire:model="latefee_date" placeholder="{{ __('Late Fee Date') }}" name="latefee_date" class="w-full mt-1" :value="old('latefee_date', $latefee_date)" autocomplete="latefee_date" />
      <x-input-error :messages="$errors->get('latefee_date')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="intmarksstart_date" :value="__('Internal Mark Start Date')" />
      <x-text-input id="intmarksstart_date" type="date" wire:model="intmarksstart_date" placeholder="{{ __('Internal Mark Start Date') }}" name="intmarksstart_date" class="w-full mt-1" :value="old('intmarksstart_date', $intmarksstart_date)" autocomplete="intmarksstart_date" />
      <x-input-error :messages="$errors->get('intmarksstart_date')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="intmarksend_date" :value="__('Internal Mark End Date')" />
      <x-text-input id="intmarksend_date" type="date" wire:model="intmarksend_date" placeholder="{{ __('Internal Mark End Date') }}" name="intmarksend_date" class="w-full mt-1" :value="old('intmarksend_date', $intmarksend_date)" autocomplete="intmarksend_date" />
      <x-input-error :messages="$errors->get('intmarksend_date')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="finefee_date" :value="__('Fine Fee Date')" />
      <x-text-input id="finefee_date" type="date" wire:model="finefee_date" placeholder="{{ __('Fine Fee Date') }}" name="finefee_date" class="w-full mt-1" :value="old('finefee_date', $finefee_date)" autocomplete="finefee_date" />
      <x-input-error :messages="$errors->get('finefee_date')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="capmaster_id" :value="__('Select Cap')" />
      <x-input-select id="capmaster_id" wire:model="capmaster_id" name="capmaster_id" class="text-center w-full mt-1" :value="old('capmaster_id', $capmaster_id)" autocomplete="capmaster_id">
        <x-select-option class="text-start" hidden> -- Select Cap -- </x-select-option>
        @forelse ($capmasters as $capid => $capname)
          <x-select-option wire:key="{{  $capid }}" value="{{ $capid }}" class="text-start"> {{ $capname ?? '-' }} </x-select-option>
        @empty
          <x-select-option class="text-start">Caps Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('capmaster_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="capscheduled_date" :value="__('Cap Scheduled Date')" />
      <x-text-input id="capscheduled_date" type="date" wire:model="capscheduled_date" placeholder="{{ __('Cap Scheduled  Date') }}" name="capscheduled_date" class="w-full mt-1" :value="old('capscheduled_date', $capscheduled_date)" autocomplete="capscheduled_date" />
      <x-input-error :messages="$errors->get('capscheduled_date')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="papersettingstart_date" :value="__('Paper Setting Start Date')" />
      <x-text-input id="papersettingstart_date" type="date" wire:model="papersettingstart_date" placeholder="{{ __('Paper Setting Start Date') }}" name="papersettingstart_date" class="w-full mt-1" :value="old('papersettingstart_date', $papersettingstart_date)" autocomplete="papersettingstart_date" />
      <x-input-error :messages="$errors->get('papersettingstart_date')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="papersubmission_date" :value="__('Paper Submission Date')" />
      <x-text-input id="papersubmission_date" type="date" wire:model="papersubmission_date" placeholder="{{ __('Paper Submission Date') }}" name="papersubmission_date" class="w-full mt-1" :value="old('papersubmission_date', $papersubmission_date)" autocomplete="papersubmission_date" />
      <x-input-error :messages="$errors->get('papersubmission_date')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="launch_status" :value="__('Launch Status')" />
      <x-input-select id="launch_status" wire:model.live="launch_status" name="launch_status" class="text-center w-full mt-1" :value="old('launch_status', $launch_status)" required autocomplete="launch_status">
        <x-select-option class="text-start" hidden>-- Select Status --</x-select-option>
        <x-select-option class="text-start" value="0">No</x-select-option>
        <x-select-option class="text-start" value="1">Yes</x-select-option>
      </x-input-select>
      <x-input-error :messages="$errors->get('launch_status')" class="mt-1" />
    </div>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="placeofmeeting" :value="__('Place Of Meeting')" />
      <x-textarea id="placeofmeeting" wire:model="placeofmeeting" placeholder="{{ __('Place Of Meeting') }}" name="placeofmeeting" class="w-full mt-1" :value="old('placeofmeeting', $placeofmeeting)" autocomplete="placeofmeeting" />
      <x-input-error :messages="$errors->get('placeofmeeting')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="description" :value="__('Description')" />
      <x-textarea id="description" wire:model="description" placeholder="{{ __('Description') }}" name="description" class="w-full mt-1" :value="old('description', $description)" autocomplete="description" />
      <x-input-error :messages="$errors->get('description')" class="mt-1" />
    </div>
  </div>
</div>
<x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
