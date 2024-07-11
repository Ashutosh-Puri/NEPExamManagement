<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Internal Tool Document's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Internal Tool Document">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="save()">
        @include('livewire.user.internal-audit.internal-tool-document.internaltool-doc-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Internal Tool Document">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $internaltooldocument_id }})">
      @include('livewire.user.internal-audit.internal-tool-document.internaltool-doc-form')
    </x-form>
  @elseif($mode == 'view')
    <x-card-header heading="View Internal Tool Document">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    @include('livewire.user.internal-audit.internal-tool-document.view-form')
  @elseif($mode == 'all')
    <div>

      <x-card-header heading="All Internal Tool Document's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('internaltoolmaster_id')" name="internaltoolmaster_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Internal Tool Name</x-table.th>
                <x-table.th wire:click="sort_column('internaltooldoc_id')" name="internaltooldoc_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Internal Tool Document Name</x-table.th>
                <x-table.th wire:click="sort_column('is_multiple')" name="is_multiple" :sort="$sortColumn" :sort_by="$sortColumnBy">Is Multiple</x-table.th>
                <x-table.th> Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($internaltool_documents as $internaltool_document)
                <x-table.tr wire:key="{{ $internaltool_document->id }}">
                  <x-table.td>{{ $internaltool_document->id }} </x-table.td>
                  <x-table.td>{{ $internaltool_document->internaltoolmaster->toolname }} </x-table.td>
                  <x-table.td>{{ $internaltool_document->internaltooldocumentmaster->doc_name }} </x-table.td>
                  <x-table.td>
                    @if ($internaltool_document->is_multiple == 1)
                      <x-status type="success">Yes</x-status>
                    @else
                      <x-status type="danger">No</x-status>
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if (!$internaltool_document->deleted_at)
                      @if ($internaltool_document->status === 1)
                        <x-table.active wire:click="changestatus({{ $internaltool_document->id }})" />
                      @else
                        <x-table.inactive wire:click="changestatus({{ $internaltool_document->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($internaltool_document->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $internaltool_document->id }})" />
                      <x-table.restore wire:click="restore({{ $internaltool_document->id }})" />
                    @else
                      <x-table.view wire:click="view({{ $internaltool_document->id }})" />
                      <x-table.edit wire:click="edit({{ $internaltool_document->id }})" />
                      <x-table.archive wire:click="softdelete({{ $internaltool_document->id }})" />
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
          <x-table.paginate :data="$internaltool_documents" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
