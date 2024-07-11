<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Pattern Class's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Pattern Class">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.pattern-class.pattern-class-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Pattern Class">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.pattern-class.pattern-class-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
    
      <x-card-header heading="All Pattern Class's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('pattern_id')" name="pattern_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern </x-table.th>
                <x-table.th wire:click="sort_column('class_id')" name="class_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Course Class</x-table.th>
                <x-table.th wire:click="sort_column('sem1_total_marks')" name="sem1_total_marks" :sort="$sortColumn" :sort_by="$sortColumnBy">Sem 1 Total Marks</x-table.th>
                <x-table.th wire:click="sort_column('sem2_total_marks')" name="sem2_total_marks" :sort="$sortColumn" :sort_by="$sortColumnBy">Sem 2 Total Marks</x-table.th>
                <x-table.th wire:click="sort_column('sem1_credits')" name="sem1_credits" :sort="$sortColumn" :sort_by="$sortColumnBy">Sem 2 Credits</x-table.th>
                <x-table.th wire:click="sort_column('sem2_credits')" name="sem2_credits" :sort="$sortColumn" :sort_by="$sortColumnBy">Sem 2 Credits</x-table.th>
                <x-table.th wire:click="sort_column('credit')" name="credit" :sort="$sortColumn" :sort_by="$sortColumnBy">ATKT Credit</x-table.th>
                <x-table.th wire:click="sort_column('sem1_totalnosubjects')" name="sem1_totalnosubjects" :sort="$sortColumn" :sort_by="$sortColumnBy">Sem 1 Total Subjects</x-table.th>
                <x-table.th wire:click="sort_column('sem2_totalnosubjects')" name="sem2_totalnosubjects" :sort="$sortColumn" :sort_by="$sortColumnBy">Sem 2 Total Subjects</x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($pattern_classes as $pattern_class)
                <x-table.tr wire:key="{{ $pattern_class->id }}">
                  <x-table.td>{{ $pattern_class->id }} </x-table.td>
                  <x-table.td>{{ $pattern_class->pattern->pattern_name }} </x-table.td>
                  <x-table.td>{{ $pattern_class->courseclass->classyear->classyear_name ?? '' }} {{ $pattern_class->courseclass->course->course_name ?? '' }} </x-table.td>
                  <x-table.td>{{ $pattern_class->sem1_total_marks }} </x-table.td>
                  <x-table.td>{{ $pattern_class->sem2_total_marks }} </x-table.td>
                  <x-table.td>{{ $pattern_class->sem1_credits }} </x-table.td>
                  <x-table.td>{{ $pattern_class->sem2_credits }} </x-table.td>
                  <x-table.td>{{ $pattern_class->credit }} </x-table.td>
                  <x-table.td>{{ $pattern_class->sem1_totalnosubjects }} </x-table.td>
                  <x-table.td>{{ $pattern_class->sem2_totalnosubjects }} </x-table.td>
                  <x-table.td>
                    @if (!$pattern_class->deleted_at)
                      @if ($pattern_class->status == 1)
                        <x-table.active wire:click="changestatus({{ $pattern_class->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $pattern_class->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($pattern_class->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $pattern_class->id }})" />
                      <x-table.restore wire:click="restore({{ $pattern_class->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $pattern_class->id }})" />
                      <x-table.archive wire:click="delete({{ $pattern_class->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$pattern_classes" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
