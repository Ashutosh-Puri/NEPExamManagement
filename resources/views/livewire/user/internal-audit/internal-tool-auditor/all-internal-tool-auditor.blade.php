<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Internal Tool Auditor's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Internal Tool Auditor">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.user.internal-audit.internal-tool-auditor.internal-tool-auditor-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Internal Tool Auditor">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $internaltool_auditor_id }})">
      @include('livewire.user.internal-audit.internal-tool-auditor.internal-tool-auditor-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Internal Tool Auditor">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.user.internal-audit.internal-tool-auditor.view-form')
  @elseif($mode == 'all')
    <div>

      <x-card-header heading="All Internal Tool Auditor's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('academicyear_id')" name="academicyear_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Academic Year</x-table.th>
                <x-table.th wire:click="sort_column('patternclass_id')" name="patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Class Name</x-table.th>
                <x-table.th wire:click="sort_column('faculty_id')" name="faculty_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Faculty Name</x-table.th>
                <x-table.th wire:click="sort_column('evaluationdate')" name="evaluationdate" :sort="$sortColumn" :sort_by="$sortColumnBy">Evaluation Date</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($internaltool_auditors as $internaltool_auditor)
                <x-table.tr wire:key="{{ $internaltool_auditor->id }}">
                  <x-table.td>{{ $internaltool_auditor->id }} </x-table.td>
                  <x-table.td>{{ isset($internaltool_auditor->academicyear->year_name) ? $internaltool_auditor->academicyear->year_name : '' }}</x-table.td>
                  <x-table.td>
                    <x-table.text-scroll>
                      {{ isset($internaltool_auditor->patternclass->pattern->pattern_name) ? $internaltool_auditor->patternclass->pattern->pattern_name : '' }} {{ isset($internaltool_auditor->patternclass->courseclass->classyear->classyear_name) ? $internaltool_auditor->patternclass->courseclass->classyear->classyear_name : '' }} {{ isset($internaltool_auditor->patternclass->courseclass->course->course_name) ? $internaltool_auditor->patternclass->courseclass->course->course_name : '' }}
                    </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll>
                      {{ isset($internaltool_auditor->faculty->faculty_name) ? $internaltool_auditor->faculty->faculty_name : '' }}
                    </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>{{ isset($internaltool_auditor->evaluationdate) ? date('d-m-Y', strtotime($internaltool_auditor->evaluationdate)) : '-' }}</x-table.td>
                  <x-table.td>
                    @if (!$internaltool_auditor->deleted_at)
                      @if ($internaltool_auditor->status === 1)
                        <x-table.active wire:click="changestatus({{ $internaltool_auditor->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $internaltool_auditor->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($internaltool_auditor->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $internaltool_auditor->id }})" />
                      <x-table.restore wire:click="restore({{ $internaltool_auditor->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $internaltool_auditor->id }})" />
                      <x-table.edit wire:click="edit({{ $internaltool_auditor->id }})" />
                      <x-table.archive wire:click="softdelete({{ $internaltool_auditor->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td colspan='8' class="text-center">No Data Found</x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$internaltool_auditors" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
