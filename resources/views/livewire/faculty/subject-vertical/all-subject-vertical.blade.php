<div>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Subject Vertical">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.faculty.subject-vertical.subject-vertical-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Subject Vertical">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $subjectvertical_id }})">
      @include('livewire.faculty.subject-vertical.subject-vertical-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Subject Vertical">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.faculty.subject-vertical.view-form')
  @elseif($mode == 'all')
    <div>
      <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Subject Vertical's" />
      </x-breadcrumb.breadcrumb>
      <x-card-header heading="All Subject Vertical's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('subjectvertical')" name="subjectvertical" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Vertical</x-table.th>
                <x-table.th wire:click="sort_column('subjectvertical_shortname')" name="subjectvertical_shortname" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Vertical Shortname</x-table.th>
                <x-table.th wire:click="sort_column('subjectbucket_type')" name="subjectbucket_type" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Bucket Type</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($subjectverticals as $subjectvertical)
                <x-table.tr wire:key="{{ $subjectvertical->id }}">
                  <x-table.td>{{ $subjectvertical->id }} </x-table.td>
                  <x-table.td>{{ $subjectvertical->subject_vertical }} </x-table.td>
                  <x-table.td>{{ $subjectvertical->subjectvertical_shortname }} </x-table.td>
                  <x-table.td>{{ $subjectvertical->buckettype->buckettype_name ?? '' }} </x-table.td>
                  <x-table.td>
                    @if (!$subjectvertical->deleted_at)
                      @if ($subjectvertical->is_active === 1)
                        <x-table.active wire:click="changestatus({{ $subjectvertical->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $subjectvertical->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($subjectvertical->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $subjectvertical->id }})" />
                      <x-table.restore wire:click="restore({{ $subjectvertical->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $subjectvertical->id }})" />
                      <x-table.edit wire:click="edit({{ $subjectvertical->id }})" />
                      <x-table.archive wire:click="softdelete({{ $subjectvertical->id }})" />
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
          <x-table.paginate :data="$subjectverticals" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
