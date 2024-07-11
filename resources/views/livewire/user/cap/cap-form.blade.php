<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Cap
  </div>
  <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
    <x-input-label for="college_id" :value="__('Select College')" />
    <x-input-select id="college_id" wire:model="college_id" name="college_id" class="text-center w-full mt-1" :value="old('college_id', $college_id)"  autocomplete="college_id">
      <x-select-option class="text-start" hidden> -- Select College -- </x-select-option>
      @forelse ($colleges as $collegeid => $collegename)
        <x-select-option wire:key="{{ $collegeid }}" value="{{$collegeid }}" class="text-start"> {{ $collegename }} </x-select-option>
      @empty
        <x-select-option class="text-start">Colleges Not Found</x-select-option>
      @endforelse
    </x-input-select>
    <x-input-error :messages="$errors->get('college_id')" class="mt-1" />
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="exam_id" :value="__('Select Exam')" />
      <x-input-select id="exam_id" wire:model="exam_id" name="exam_id" class="text-center w-full mt-1" :value="old('exam_id', $exam_id)"  autocomplete="exam_id">
        <x-select-option class="text-start" hidden> -- Select Exam -- </x-select-option>
        @forelse ($exams as $examid => $examname)
          <x-select-option wire:key="{{  $examid }}" value="{{ $examid }}" class="text-start"> {{ $examname }} </x-select-option>
        @empty
          <x-select-option class="text-start">Exams Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('exam_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="cap_name" :value="__('Cap Name')" />
      <x-text-input id="cap_name" type="text" wire:model="cap_name" placeholder="{{ __('Enter Cap Name') }}" name="cap_name" class="w-full mt-1" :value="old('cap_name', $cap_name)" autocomplete="cap_name" />
      <x-input-error :messages="$errors->get('cap_name')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="place" :value="__('Place')" />
      <x-textarea id="place" type="text" wire:model="place" placeholder="{{ __('Enter Place') }}" name="place" class="w-full mt-1" :value="old('place', $place)" autocomplete="place" />
      <x-input-error :messages="$errors->get('place')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="status" :value="__('Status')" /> <br>
      <x-input-checkbox id="status" wire:model="status" name="status" :value="old('status', $status)" />
      <x-input-label for="status" class="inline mb-1 mx-2" :value="__('Make In Active')" />
      <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
  </div>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
