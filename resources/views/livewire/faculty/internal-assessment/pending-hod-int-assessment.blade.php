<div>
  <div>
    <x-card-header heading="HOD Subject Internal Tool Assessment Report's">
      <x-add-btn wire:click="setmode('add')" />
    </x-card-header>
    <x-table.frame s="0">
      <x-slot:header>
        <div class="flex gap-x-0.5">
          <x-input-select id="acdemicyear_id" wire:model.live="acdemicyear_id" name="acdemicyear_id" class="text-center h-10" :value="old('acdemicyear_id', $acdemicyear_id)" autocomplete="acdemicyear_id">
            <x-select-option class="text-start" hidden> -- Select Acdemic Year -- </x-select-option>
            @forelse ($acdemicyears as $acdemicyear)
              <x-select-option wire:key="{{ $acdemicyear->id }}" value="{{ $acdemicyear->id }}" class="text-start"> {{ $acdemicyear->year_name }} </x-select-option>
            @empty
              <x-select-option class="text-start">Acdemic Years Not Found</x-select-option>
            @endforelse
          </x-input-select>
          <x-input-error :messages="$errors->get('acdemicyear_id')" class="mt-1" />
          <span class="h-10">
            <x-table.cancel class="mx-0.5 py-0.5 h-10" wire:click='reset_input()' i="0"> Clear</x-table.cancel>
          </span>
        </div>
      </x-slot>
      <x-slot:body>
        <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
          <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            HOD subject internal tools yet to be assessed.
          </div>
          <div class="overflow-x-scroll">
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th>ID</x-table.th>
                  <x-table.th>Academic Year</x-table.th>
                  <x-table.th>Subject Name</x-table.th>
                  <x-table.th>Assessment</x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @forelse ($not_uploaded_documents as  $not_uploaded_document)
                  <x-table.tr>
                    <x-table.td>{{ $not_uploaded_document->subject_id }}</x-table.td>
                    <x-table.td>{{ $not_uploaded_document->subject->academicyear->year_name }}</x-table.td>
                    <x-table.td>{{ $not_uploaded_document->subject->subject_code }} {{ $not_uploaded_document->subject->subject_name }}</x-table.td>
                    <x-table.td> NO </x-table.td>
                  </x-table.tr>
                @empty
                  <x-table.tr>
                    <x-table.td colspan='4' class="text-center">No Data Found</x-table.td>
                  </x-table.tr>
                @endforelse
              </x-table.tbody>
            </x-table.table>
            <x-table.paginate :data="$not_uploaded_documents" />
          </div>
        </div>
        <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
          <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            HOD subject internal tools that have already been assessed.
          </div>
          <div class="overflow-x-scroll">
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th>ID</x-table.th>
                  <x-table.th>Academic Year</x-table.th>
                  <x-table.th>Subject Name</x-table.th>
                  <x-table.th>Assessment</x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @forelse ($uploaded_documents as  $uploaded_document)
                  <x-table.tr>
                    <x-table.td>{{ $uploaded_document->subject_id }}</x-table.td>
                    <x-table.td>{{ $uploaded_document->academicyear->year_name }}</x-table.td>
                    <x-table.td>{{ $uploaded_document->subject->subject_code }} {{ $uploaded_document->subject->subject_name }}</x-table.td>
                    <x-table.td> Yes {{-- $uploaded_document->facultyinternaldocuments_count --}} </x-table.td>
                  </x-table.tr>
                @empty
                  <x-table.tr>
                    <x-table.td colspan='4' class="text-center">No Data Found</x-table.td>
                  </x-table.tr>
                @endforelse
              </x-table.tbody>
            </x-table.table>
            <x-table.paginate :data="$uploaded_documents" />
          </div>
        </div>
      </x-slot>
    </x-table.frame>
  </div>
</div>
