<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Educational Course's" />
    </x-breadcrumb.link>
    @if ($mode == 'add')
      <div>
        <x-card-header heading=" Add Educational Course">

          <x-back-btn wire:click="setmode('all')" />

        </x-card-header>
        <x-form wire:submit="add()">
          @include('livewire.user.educational-course.educational-course-form')
        </x-form>
      </div>
    @elseif($mode == 'edit')
      <x-card-header heading="Edit Educational Course">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $course_id }})">
        @include('livewire.user.educational-course.educational-course-form')
      </x-form>
    @elseif($mode == 'all')
      <div>
        <x-card-header heading=" All Educational Course's">
          <x-add-btn wire:click="setmode('add')" />
        </x-card-header>
        <x-table.frame>
          <x-slot:header>
          </x-slot>
          <x-slot:body>
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">No.</x-table.th>
                  <x-table.th wire:click="sort_column('course_name')" name="course_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Course Name </x-table.th>
                  <x-table.th wire:click="sort_column('programme_id')" name="programme_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Program Name</x-table.th>
                  <x-table.th wire:click="sort_column('is_active')" name="is_active" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                  <x-table.th> Action </x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @forelse ($educationalCourses as  $educationalCourse)
                  <x-table.tr wire:key="{{ $educationalCourse->id }}">
                    <x-table.td> {{ $educationalCourse->id }}</x-table.td>
                    <x-table.td>
                      <x-table.text-scroll> {{ $educationalCourse->course_name }} </x-table.text-scroll>
                    </x-table.td>
                    <x-table.td> {{ $educationalCourse->programme?->programme_name }} </x-table.td>

                    <x-table.td>
                      @if ($educationalCourse->deleted_at)
                      @elseif($educationalCourse->is_active == 1)
                        <x-table.active wire:click="status({{ $educationalCourse->id }})" />
                      @else
                        <x-table.inactive wire:click="status({{ $educationalCourse->id }})" />
                      @endif
                    </x-table.td>
                    <x-table.td>
                      @if ($educationalCourse->deleted_at)
                        <x-table.delete wire:click="deleteconfirmation({{ $educationalCourse->id }})" />
                        <x-table.restore wire:click="restore({{ $educationalCourse->id }})" />
                      @else
                        <x-table.edit wire:click="edit({{ $educationalCourse->id }})" />
                        <x-table.archive wire:click="delete({{ $educationalCourse->id }})" />
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
            <x-table.paginate :data="$educationalCourses" />
          </x-slot>
        </x-table.frame>
      </div>
    @endif
</div>
