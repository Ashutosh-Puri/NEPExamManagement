<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard"/>
    <x-breadcrumb.link name="Course Class's"/>
  </x-breadcrumb.breadcrumb>
    @if ($mode == 'add')
      <div>
        <x-card-header  heading="Add Course Class">
          <x-back-btn wire:click="setmode('all')" />
        </x-card-header>
        <x-form wire:submit="add()">
          @include('livewire.user.course-class.course-class-form')
        </x-form>
      </div>
    @elseif($mode=='edit')
      <div>
          <x-card-header  heading="Edit Course Class">
            <x-back-btn wire:click="setmode('all')" />
          </x-card-header>
          <x-form wire:submit="update({{ $edit_id }})" >
             @include('livewire.user.course-class.course-class-form')
          </x-form>
      </div>
    @elseif($mode == 'all')
      <div>
        
        <x-card-header heading="All Course Class's">
          <x-add-btn wire:click="setmode('add')" />
        </x-card-header>
        <x-table.frame>
          <x-slot:body>
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                  <x-table.th wire:click="sort_column('course_id')" name="course_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Class </x-table.th>
                  <x-table.th wire:click="sort_column('nextyearclass_id')" name="nextyearclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Next Class</x-table.th>
                  <x-table.th wire:click="sort_column('college_id')" name="college_id" :sort="$sortColumn" :sort_by="$sortColumnBy">College</x-table.th>
                  <x-table.th> Action </x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @foreach ($course_classes as $course_class)
                  <x-table.tr wire:key="{{$course_class->id }}">
                    <x-table.td>{{  $course_class->id }} </x-table.td>
                    <x-table.td>{{  isset($course_class->classyear->classyear_name)? $course_class->classyear->classyear_name:'-'; }}  {{ isset($course_class->course->course_name)? $course_class->course->course_name:'-'; }} </x-table.td>
                    <x-table.td>  {{ isset($course_class->courseclass->classyear->classyear_name)?  $course_class->courseclass->classyear->classyear_name:'-'; }}  {{ isset($course_class->courseclass->course->course_name) ?$course_class->courseclass->course->course_name :'-' }} </x-table.td>
                    <x-table.td>
                        <x-table.text-scroll>
                            {{  isset($course_class->college->college_name)?$course_class->college->college_name:''; }} 
                        </x-table.text-scroll>
                    </x-table.td>
                    <x-table.td>
                        @if ($course_class->deleted_at)
                          <x-table.delete  wire:click="deleteconfirmation({{ $course_class->id }})" />
                          <x-table.restore  wire:click="restore({{$course_class->id }})" />
                        @else
                          <x-table.edit wire:click="edit({{$course_class->id }})" />
                          <x-table.archive  wire:click="delete({{ $course_class->id }})" />
                        @endif
                      </x-table.td>
                  </x-table.tr>
                @endforeach
              </x-table.tbody>
            </x-table.table>
          </x-slot>
          <x-slot:footer>
            <x-table.paginate :data="$course_classes" />
          </x-slot>
        </x-table.frame>
      </div>
    @endif
  </div>
  