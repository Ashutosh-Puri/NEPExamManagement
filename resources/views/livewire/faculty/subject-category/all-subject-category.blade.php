<div>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Subject Category">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.faculty.subject-category.subject-category-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Subject Category">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $subjectcategory_id }})">
      @include('livewire.faculty.subject-category.subject-category-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Subject Category">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.faculty.subject-category.view-form')
  @elseif($mode == 'all')
    <div>
      <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Subject Categorie's" />
      </x-breadcrumb.breadcrumb>
      <x-card-header heading="All Subject Categorie's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('subjectcategory')" name="subjectcategory" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Category</x-table.th>
                <x-table.th wire:click="sort_column('subjectcategory_shortname')" name="subjectcategory_shortname" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Category Shortname</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($subjectcategories as $subjectcategory)
                <x-table.tr wire:key="{{ $subjectcategory->id }}">
                  <x-table.td>{{ $subjectcategory->id }} </x-table.td>
                  <x-table.td>{{ $subjectcategory->subjectcategory }} </x-table.td>
                  <x-table.td>{{ $subjectcategory->subjectcategory_shortname }} </x-table.td>
                  <x-table.td>
                    @if (!$subjectcategory->deleted_at)
                      @if ($subjectcategory->active === 1)
                        <x-table.active wire:click="changestatus({{ $subjectcategory->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $subjectcategory->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($subjectcategory->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $subjectcategory->id }})" />
                      <x-table.restore wire:click="restore({{ $subjectcategory->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $subjectcategory->id }})" />
                      <x-table.edit wire:click="edit({{ $subjectcategory->id }})" />
                      <x-table.archive wire:click="softdelete({{ $subjectcategory->id }})" />
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
          <x-table.paginate :data="$subjectcategories" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
