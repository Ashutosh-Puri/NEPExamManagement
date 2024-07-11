<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard"/>
    <x-breadcrumb.link name="Course's"/>
  </x-breadcrumb.breadcrumb>
    @if ($mode == 'add')
      <div>
        <x-card-header  heading="Add Course">
          <x-back-btn wire:click="setmode('all')" />
        </x-card-header>
        <x-form wire:submit="add()">
          @include('livewire.user.course.course-form')
        </x-form>
      </div>
    @elseif($mode=='edit')
      <div>
          <x-card-header  heading="Edit Course">
            <x-back-btn wire:click="setmode('all')" />
          </x-card-header>
          <x-form wire:submit="update({{ $edit_id }})" >
             @include('livewire.user.course.course-form')
          </x-form>
      </div>
    @elseif($mode == 'all')
      <div>
       
        <x-card-header heading="All Course's">
          <x-add-btn wire:click="setmode('add')" />
        </x-card-header>
        <x-table.frame>
          <x-slot:body>
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                  <x-table.th wire:click="sort_column('course_name')" name="course_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Course</x-table.th>
                  <x-table.th wire:click="sort_column('course_code')" name="course_code" :sort="$sortColumn" :sort_by="$sortColumnBy">Code</x-table.th>
                  <x-table.th wire:click="sort_column('fullname')" name="fullname" :sort="$sortColumn" :sort_by="$sortColumnBy">Full Name</x-table.th>
                  <x-table.th wire:click="sort_column('special_subject')" name="special_subject" :sort="$sortColumn" :sort_by="$sortColumnBy">Special Subject</x-table.th>
                  <x-table.th wire:click="sort_column('course_type')" name="course_type" :sort="$sortColumn" :sort_by="$sortColumnBy">Course Type</x-table.th>
                  <x-table.th wire:click="sort_column('course_category')" name="course_category" :sort="$sortColumn" :sort_by="$sortColumnBy">Course Category</x-table.th>
                  <x-table.th wire:click="sort_column('college_id')" name="college_id" :sort="$sortColumn" :sort_by="$sortColumnBy">College</x-table.th>
                  <x-table.th wire:click="sort_column('programme_id')" name="programme_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Programme</x-table.th>
                  <x-table.th> Action </x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @foreach ($courses as $course)
                  <x-table.tr wire:key="{{ $course->id }}">
                    <x-table.td>{{ $course->id }} </x-table.td>
                    <x-table.td>{{ $course->course_name }} </x-table.td>
                    <x-table.td>{{ $course->course_code }} </x-table.td>
                    <x-table.td>  <x-table.text-scroll> {{ $course->fullname }}  </x-table.text-scroll></x-table.td>
                    <x-table.td>{{ $course->special_subject }} </x-table.td>
                    <x-table.td>{{ $course->course_type }} </x-table.td>
                    <x-table.td>{{ $course->coursecategory->course_category }} </x-table.td>
                    <x-table.td >
                      <x-table.text-scroll> {{ isset($course->college->college_name)?$course->college->college_name:''; }}</x-table.text-scroll>
                    </x-table.td>
                    <x-table.td>{{ isset($course->programme->programme_name)?$course->programme->programme_name:''; }} </x-table.td>
                    <x-table.td>
                      @if ($course->deleted_at)
                        <x-table.delete  wire:click="deleteconfirmation({{ $course->id }})" />
                        <x-table.restore  wire:click="restore({{ $course->id }})" />
                      @else
                        <x-table.edit wire:click="edit({{ $course->id }})" />
                        <x-table.archive  wire:click="delete({{ $course->id }})" />
                      @endif
                    </x-table.td>
                  </x-table.tr>
                @endforeach
              </x-table.tbody>
            </x-table.table>
          </x-slot>
          <x-slot:footer>
            <x-table.paginate :data="$courses" />
          </x-slot>
        </x-table.frame>
      </div>
    @endif
  </div>
  