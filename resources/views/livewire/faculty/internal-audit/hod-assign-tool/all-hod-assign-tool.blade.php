<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Tool Document Report's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'view')
    <div>
      <x-card-header heading="View Uploaded Documents">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      @include('livewire.faculty.internal-audit.hod-assign-tool.view-tool-document')
    </div>
  @elseif ($mode == 'all')
    <div>
      <x-card-header heading="Tool Document Report's">
      </x-card-header>
      <x-table.frame>
        <x-slot:header>
          <div class="flex gap-x-0.5">
            <x-input-select id="academicyear_id" wire:model.live="academicyear_id" name="academicyear_id" class="text-center h-10">
              <x-select-option class="text-start" hidden>Year </x-select-option>
              @foreach ($academicyears as $academicyear)
                <x-select-option wire:key="{{ $academicyear->id }}" value="{{ $academicyear->id }}" class="text-start">{{ $academicyear->year_name }}</x-select-option>
              @endforeach
            </x-input-select>
            <x-table.cancel class="mx-2" wire:click='resetinput()' i="0"> Clear</x-table.cancel>
          </div>
        </x-slot:header>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('academicyear_id')" name="academicyear_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Academic Year</x-table.th>
                <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Name</x-table.th>
                <x-table.th>Total Required Documents</x-table.th>
                <x-table.th>Uploaded Documents</x-table.th>
                <x-table.th>Remaining Documents</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($groupedInternalDocuments as $academicYearId => $academicYearData)
                @php
                  $total_documents = 0;
                  $uploaded_documents = 0;
                  $not_uploaded_documents = 0;
                @endphp
                @foreach ($academicYearData as $subjectId => $internalDocuments)
                  @php
                    $total_documents = $internalDocuments->count();
                    $not_uploaded_documents = $internalDocuments->whereNull('document_fileName')->whereNull('document_filePath')->count();
                    $uploaded_documents = $internalDocuments->whereNotNull('document_fileName')->whereNotNull('document_filePath')->count();
                  @endphp
                  <x-table.tr wire:key="{{ $subjectId }}">
                    <x-table.td>{{ $internalDocuments->first()->id }}</x-table.td>
                    <x-table.td>{{ $internalDocuments->first()->facultysubjecttool->academicyear->year_name ?? '' }}</x-table.td>
                    <x-table.td>
                      <x-table.text-scroll>{{ $internalDocuments->first()->facultysubjecttool->subject->subject_code }} {{ $internalDocuments->first()->facultysubjecttool->subject->subject_name }}</x-table.text-scroll>
                    </x-table.td>
                    <x-table.td>{{ $total_documents }}</x-table.td>
                    <x-table.td>{{ $uploaded_documents }}</x-table.td>
                    <x-table.td>{{ $not_uploaded_documents }}</x-table.td>
                    <x-table.td>
                      @if ($this->show_freeze_button($internalDocuments->first()->facultysubjecttool_id))
                        <x-table.approve wire:click='freeze_tool({{ $internalDocuments->first()->facultysubjecttool_id }})' />
                      @endif
                      @if (!$internalDocuments->first()->deleted_at)
                        <x-table.view wire:click="view({{ $internalDocuments->first()->id }})" />
                        <form method="post" action="{{ route('faculty.download_subject_internal_tool_document_report') }}" style="display: inline;">
                          @csrf
                          <input type="hidden" name="subject_id" value="{{ $internalDocuments->first()->facultysubjecttool->subject_id }}">
                          <x-table.download type="submit" />
                        </form>
                      @endif
                    </x-table.td>
                  </x-table.tr>
                @endforeach
              @empty
                <x-table.tr>
                  <x-table.td colspan='5' class="text-center">No Data Found</x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$faculty_internal_documents" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
