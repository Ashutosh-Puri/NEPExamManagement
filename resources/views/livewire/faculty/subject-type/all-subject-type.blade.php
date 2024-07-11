<div>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Subject Type">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.faculty.subject-type.subjecttype-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Subject Type">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $subjecttype_id }})">
      @include('livewire.faculty.subject-type.subjecttype-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Subject Type">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.faculty.subject-type.view-form')
  @elseif($mode == 'all')
    <div>
      <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Subject Type's" />
      </x-breadcrumb.breadcrumb>
      <x-card-header heading="All Subject Type's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('type_name')" name="type_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Type Name</x-table.th>
                <x-table.th wire:click="sort_column('description')" name="description" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Type Description</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($subjecttypes as $subjecttype)
                <x-table.tr wire:key="{{ $subjecttype->id }}">
                  <x-table.td> {{ $subjecttype->id }} </x-table.td>
                  <x-table.td> {{ $subjecttype->type_name }} </x-table.td>
                  <x-table.td> {{ $subjecttype->description }} </x-table.td>
                  <x-table.td>
                    @if (!$subjecttype->deleted_at)
                      @if ($subjecttype->is_active === 1)
                        <x-table.active wire:click="changestatus({{ $subjecttype->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $subjecttype->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($subjecttype->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $subjecttype->id }})" />
                      <x-table.restore wire:click="restore({{ $subjecttype->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $subjecttype->id }})" />
                      <x-table.edit wire:click="edit({{ $subjecttype->id }})" />
                      <x-table.archive wire:click="softdelete({{ $subjecttype->id }})" />
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
          <x-table.paginate :data="$subjecttypes" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
