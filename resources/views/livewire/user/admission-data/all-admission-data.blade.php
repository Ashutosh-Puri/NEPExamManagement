<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard"/>
    <x-breadcrumb.link name="Admission Data's"/>
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Admission Data Entry">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.admission-data.admission-data-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Admission Data Entry">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $edit_id }})">
      @include('livewire.user.admission-data.admission-data-form')
    </x-form>
  @elseif($mode == 'all' || $mode == 'import')
    <div>
      <x-card-header heading=" All Admission Data's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      @if ($mode == 'import')
        @livewire('user.admission-data.import-admission-data')
      @endif
      <x-table.frame i="1" :mode="$mode">
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">No.</x-table.th>
                <x-table.th wire:click="sort_column('academicyear_id')" name="academicyear_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Academic Year</x-table.th>
                <x-table.th wire:click="sort_column('memid')" name="memid" :sort="$sortColumn" :sort_by="$sortColumnBy">Member ID</x-table.th>
                <x-table.th wire:click="sort_column('stud_name')" name="stud_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Student</x-table.th>
                <x-table.th wire:click="sort_column('subject_id')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject </x-table.th>
                <x-table.th wire:click="sort_column('patternclass_id')" name="patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Class</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($admission_datas as $admission_data)
                <x-table.tr wire:key="{{ $admission_data->id }}">
                  <x-table.td>{{ $admission_data->id }}</x-table.td>
                  <x-table.td>{{ isset($admission_data->academicyear->year_name)?$admission_data->academicyear->year_name:''; }} </x-table.td>
                  <x-table.td>{{ $admission_data->memid }} </x-table.td>
                  <x-table.td><x-table.text-scroll> {{ $admission_data->stud_name }}</x-table.text-scroll> </x-table.td>
                  <x-table.td><x-table.text-scroll> {{  isset($admission_data->subject->subject_name)?$admission_data->subject->subject_name:'';}}</x-table.text-scroll> </x-table.td>
                  <x-table.td><x-table.text-scroll class="w-70">  {{ isset($admission_data->patternclass->courseclass->classyear->classyear_name)?$admission_data->patternclass->courseclass->classyear->classyear_name:'-'; }} {{ isset($admission_data->patternclass->courseclass->course->course_name)?$admission_data->patternclass->courseclass->course->course_name:''; }} {{ isset($admission_data->patternclass->pattern->pattern_name)?$admission_data->patternclass->pattern->pattern_name:'-'; }}</x-table.text-scroll></x-table.td>
                  <x-table.td>
                    @if ( $admission_data->deleted_at)
                        <x-table.delete  wire:click="deleteconfirmation({{ $admission_data->id }})" />
                        <x-table.restore  wire:click="restore({{ $admission_data->id }})" />
                      @else
                        <x-table.edit wire:click="edit({{ $admission_data->id }})" />
                        <x-table.archive  wire:click="delete({{ $admission_data->id }})" />
                      @endif
                  </x-table.td>
                </x-table.tr>
              @empty
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$admission_datas" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
