<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Paper Issue's" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Paper Issue" />
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Paper Issue <x-spinner />
    </div>
    <div>
      <div class="grid grid-cols-1 md:grid-cols-3">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="lotnumber" :value="__('Enter Lot Number')" />
          <x-text-input id="lotnumber" type="text" wire:model.live.debounce.500ms="lotnumber" placeholder="{{ __('Enter Lot Number') }}" name="lotnumber" class="w-full mt-1" :value="old('lotnumber', $lotnumber)" autocomplete="lotnumber" />
          <x-input-error :messages="$errors->get('lotnumber')" class="mt-1" />
        </div>
        @if ($examiner)
          <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="selectedexaminer" :value="__('Select Examiner')" />
            <x-input-select id="selectedexaminer" wire:model.live="selectedexaminer" name="selectedexaminer" class="text-center w-full mt-1" required autocomplete="selectedexaminer">
              <x-select-option class="text-start" hidden> -- Select Examiner -- </x-select-option>
              @forelse ($examiner as $ep)
                <x-select-option wire:key="{{ $ep->id }}" value="{{ $ep->id }}" class="text-start"> {{ $ep->faculty_name }} </x-select-option>
              @empty
                <x-select-option class="text-start">Examiners Not Found</x-select-option>
              @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('selectedexaminer')" class="mt-1" />
          </div>
        @endif
        @if ($moderator)
          <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="selectedmoderator" :value="__('Select Moderator')" />
            <x-input-select id="selectedmoderator" wire:model.live="selectedmoderator" name="selectedmoderator" class="text-center w-full mt-1" required autocomplete="selectedmoderator">
              <x-select-option class="text-start" hidden> -- Select Moderator -- </x-select-option>
              @forelse ($moderator as $m)
                <x-select-option wire:key="{{ $m->id }}" value="{{ $m->id }}" class="text-start"> {{ $m->faculty_name }} </x-select-option>
              @empty
                <x-select-option class="text-start">Moderators Not Found</x-select-option>
              @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('selectedmoderator')" class="mt-1" />
          </div>
        @endif
      </div>
      <div>
        <x-status class="py-2 m-2 float-end uppercase"  wire:click="addexaminer_moderator">Add</x-status>
        <x-status class="py-2 m-2 float-left"  wire:click="user_wise_report">User Wise Report</x-status>
        <x-status class="py-2 m-2 float-left"  wire:click="date_wise_report">Date Wise Report</x-status>
      </div>
      <div>
        <x-table.table>
          <x-table.thead>
            <x-table.tr>
              <x-table.th>Sr.No</x-table.th>
              <x-table.th>Lot Number </x-table.th>
              <x-table.th>Subject Code and Name </x-table.th>
              <x-table.th>Examiner</x-table.th>
              <x-table.th>Moderator </x-table.th>
            </x-table.tr>
          </x-table.thead>
          <x-table.tbody>
            @foreach ($allpaperissue as $data)
              <x-table.tr wire:key="{{ $data->id }}">
                <x-table.td>{{ $loop->iteration }} </x-table.td>
                <x-table.td>{{ $data->id }} </x-table.td>
                <x-table.td>{{ $data->exambarcodes->first()->subject->subject_code ?? '' }} {{ $data->exambarcodes->first()->subject->subject_name ?? '' }}</x-table.td>
                <x-table.td>{{ $data->examiner->faculty_name ?? '' }}</x-table.td>
                <x-table.td>{{ $data->moderator->faculty_name ?? '' }}</x-table.td>
              </x-table.tr>
            @endforeach
          </x-table.tbody>
        </x-table.table>
        {{-- <x-table.paginate :data="$allpaperissue" /> --}}
      </div>
    </div>

  </div>
</div>
