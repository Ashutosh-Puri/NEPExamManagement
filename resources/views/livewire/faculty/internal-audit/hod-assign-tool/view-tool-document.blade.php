<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Internal Tool Documents
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="academicyear_id" :value="__('Academic Year')" />
      <x-input-show id="academicyear_id" :value="$academicyear_id" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="faculty_id" :value="__('Faculty Name')" />
      <x-input-show id="faculty_id" :value="$faculty_id" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="subject_id" :value="__('Subject Name')" />
      <x-input-show id="subject_id" :value="$subject_id" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="departmenthead_id" :value="__('Department Head Name')" />
      <x-input-show id="departmenthead_id" :value="$departmenthead_id" />
    </div>
  </div>
</div>
<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <x-table.table>
    <x-table.thead>
      <x-table.tr>
        <x-table.th>Tool Name</x-table.th>
        <x-table.th>Document Name</x-table.th>
        <x-table.th>Status</x-table.th>
        <x-table.th>Action</x-table.th>
      </x-table.tr>
    </x-table.thead>
    <x-table.tbody>
      @forelse ($internaltooldocuments as $internaltooldoc)
        <x-table.tr wire:key="{{ $internaltooldoc->id }}">
          <x-table.td> {{ $internaltooldoc->internaltooldocument->internaltoolmaster->toolname ?? '' }}</x-table.td>
          <x-table.td> {{ $internaltooldoc->internaltooldocument->internaltooldocumentmaster->doc_name ?? '' }}</x-table.td>
          <x-table.td>
            @if ($internaltooldoc->document_fileName !== null && $internaltooldoc->document_filePath !== null)
              <x-status type="success">Uploaded</x-status>
            @else
              <x-status type="danger"> Not Uploaded</x-status>
            @endif
          </x-table.td>
          <x-table.td>
            @if ($internaltooldoc->document_fileName !== null && $internaltooldoc->document_filePath !== null)
              <x-view-image-model-btn title="{{ $internaltooldoc->internaltooldocument->internaltooldocumentmaster->doc_name }}" i="1" src="{{ isset($internaltooldoc->document_filePath) ? asset($internaltooldoc->document_filePath) : asset('img/no-img.png') }}" />
            @endif
          </x-table.td>
        </x-table.tr>
      @empty
        <x-table.tr>
          <x-table.td colspan='4' class="text-center">No Data Found</x-table.td>
        </x-table.tr>
      @endforelse
    </x-table.tbody>
  </x-table.table>
</div>
