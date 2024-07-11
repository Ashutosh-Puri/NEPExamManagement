<div>
  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
    <x-input-label for="allsession" :value="__('Select Exam Session')" />
    <x-input-select id="allsession" wire:model.live="selectedallsession" name="allsession" class="text-center w-full mt-1" :value="old('allsession', $allsession)" required autocomplete="allsession">
      <x-select-option class="text-start" hidden> -- Select Exam Session -- </x-select-option>
      @forelse ($allsession as $type)
          <x-select-option wire:key="{{ $type->id }}" value="{{ $type->id}}" class="text-start">   {{ date('d-M-Y', strtotime($type->from_date)) }} To {{ date('d-M-Y', strtotime($type->to_date)) }} </x-select-option>
      @empty
        <x-select-option class="text-start">Exam Sessions Not Found</x-select-option>
      @endforelse
    </x-input-select>
    <x-input-error :messages="$errors->get('allsession')" class="mt-1" />
  </div>
  <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
    <x-input-label for="dates" :value="__('Select Exam Date')" />
    <x-input-select id="dates"  name="dates" class="text-center w-full mt-1"  required autocomplete="dates">
      <x-select-option class="text-start" hidden> -- Select Exam Date -- </x-select-option>
      @forelse ($dates as $key => $dt)
          <x-select-option wire:key="{{ $dt }}" value="{{ $dt }}" class="text-start">  {{ $dt }} </x-select-option>
      @empty
        <x-select-option class="text-start">Exam Dates Not Found</x-select-option>
      @endforelse
    </x-input-select>
    <x-input-error :messages="$errors->get('dates')" class="mt-1" />
  </div>
</div>
