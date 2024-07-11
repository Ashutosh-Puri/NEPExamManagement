
<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Marks Entry Verification" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Marks Entry Verification">
    <a wire:navigate href="{{ route('user.pending_external_marks_entry_verify') }}" class="mx-2"><x-status class="py-2" type="danger">Pending Verified External Marks Report</x-status></a>
  </x-card-header>
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Marks Entry Verification
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4">
      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="lot_number" :value="__('Enter Lot Number')" />
        <x-text-input id="lot_number" type="text" wire:model="lot_number" placeholder="{{ __('Enter Lot Number') }}" name="lot_number" class="w-full mt-1" :value="old('lot_number', $lot_number)" autocomplete="lot_number" />
        <x-input-error :messages="$errors->get('lot_number')" class="mt-1" />
      </div>
      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="bundel_number" :value="__('Enter Bundle Number')" />
        <x-text-input id="bundel_number" type="text" wire:model="bundel_number" placeholder="{{ __('Enter Bundle Number') }}" name="bundel_number" class="w-full mt-1" :value="old('bundel_number', $bundel_number)" autocomplete="bundel_number" />
        <x-input-error :messages="$errors->get('bundel_number')" class="mt-1" />
      </div>
      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="bag_number" :value="__('Enter Bag Number')" />
        <x-text-input id="bag_number" type="text" wire:model="bag_number" placeholder="{{ __('Enter Bag Number') }}" name="bag_number" class="w-full mt-1" :value="old('bag_number', $bag_number)" autocomplete="bag_number" />
        <x-input-error :messages="$errors->get('bag_number')" class="mt-1" />
      </div>
      <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-primary-button wire:click="find_lot_papers" class="m-3 mt-5 float-end block">Find Papers</x-primary-button>
      </div>
    </div>
    <section>
      @php
        $chunks = $exambarcode->chunk($chunk_size);
      @endphp
      <div class="grid grid-cols-1 md:grid-cols-2">
        {{-- Left Side --}}
        <div>
          @if (count($chunks->get(0, collect())))
          <div class="overflow-x-scroll">
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th class="w-[125px]">Barcode</x-table.th>
                  <x-table.th class="w-[125px]">Marks</x-table.th>
                  <x-table.th class="w-full">Verification Remark</x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @forelse ($chunks->get(0, collect()) as $data)
                  <x-table.tr wire:key="{{ $data->id }}">
                    <x-table.td class="w-[125px]">{{ $data->id }} </x-table.td>
                    <x-table.td class="w-[125px]">
                      <x-text-input id="verified_marks.{{ $data->id }}" type="number" wire:model.live="verified_marks.{{ $data->id }}" placeholder="Enter Marks" name="verified_marks.{{ $data->id }}" class="w-[125px] h-8 mt-1" autocomplete="verified_marks.{{ $data->id }}" />
                        @error("verified_marks.{$data->id}")
                        <div class="text-sm text-red-600 dark:text-red-400 space-y-1">{{ $message }}</div>
                      @enderror
                    </x-table.td>
                    <x-table.td class="w-full">
                      @if (isset($verified_marks[$data->id]))
                        @if ((is_null($data->moderator_marks) ? $data->examiner_marks : $data->moderator_marks) != $verified_marks[$data->id])
                          <x-status type="danger">Enter Valid Marks Or Verify Examiner Marks</x-status>
                        @endif
                      @endif
                    </x-table.td>
                  </x-table.tr>
                @empty
                  @if ($lot_number)
                    <x-table.tr>
                      <x-table.td colspan='3' class="text-center">No Data Found</x-table.td>
                    </x-table.tr>
                  @else
                    <x-table.tr>
                      <x-table.td colspan='3' class="text-center">Enter Lot Number To Load Data </x-table.td>
                    </x-table.tr>
                  @endif
                @endforelse
              </x-table.tbody>
            </x-table.table>
          </div>
          @endif
        </div>
        {{-- Right Side --}}
        <div>
          @if (count($chunks->get(1, collect())))
          <div class="overflow-x-scroll">
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th class="w-[125px]">Barcode</x-table.th>
                  <x-table.th class="w-[125px]">Marks</x-table.th>
                  <x-table.th class="w-full">Verification Remark</x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @forelse ($chunks->get(1, collect()) as $data1)
                  <x-table.tr wire:key="{{ $data1->id }}">
                    <x-table.td class="w-[125px]">{{ $data1->id }} </x-table.td>
                    <x-table.td class="w-[125px]">
                      <x-text-input id="verified_marks.{{ $data1->id }}" type="number" wire:model.live="verified_marks.{{ $data1->id }}" placeholder="Enter Marks" name="verified_marks.{{ $data1->id }}" class="w-[125px] h-8 mt-1" autocomplete="verified_marks.{{ $data1->id }}" />
                    </x-table.td>
                    <x-table.td class="w-full">
                      @if (isset($verified_marks[$data1->id]))
                        @if ((is_null($data1->moderator_marks) ? $data1->examiner_marks : $data1->moderator_marks) != $verified_marks[$data1->id])
                          <x-status type="danger">Enter Valid Marks Or Verify Examiner Marks</x-status>
                        @endif
                      @endif
                    </x-table.td>
                  </x-table.tr>
                @empty
                  @if ($lot_number)
                  @if (count($exambarcode) > $chunk_size)
                  @else
                    <x-table.tr>
                      <x-table.td colspan='3' class="text-center">No Data Found</x-table.td>
                    </x-table.tr>
                  @endif
                  @else
                    <x-table.tr>
                      <x-table.td colspan='3' class="text-center">Enter Lot Number To Load Data </x-table.td>
                    </x-table.tr>
                  @endif
                @endforelse
              </x-table.tbody>
            </x-table.table>
          </div>
          @endif
        </div>
      </div>
      <div class="w-full">
        @if (count($exambarcode) > 0 && isset($lot_number) && count($exambarcode) <= 60)
          <x-table.tr>
            <x-table.td colspan="3">
              <x-form-btn wire:loading.attr="disabled" wire:click="save_final_marks">Save & Finish</x-form-btn>
            </x-table.td>
          </x-table.tr>
        @endif
      </div>
    </section>
  </div>
</div>
