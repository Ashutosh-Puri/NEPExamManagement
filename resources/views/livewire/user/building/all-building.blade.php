<div>
    <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="user.dashboard" name="Dashboard"/>
        <x-breadcrumb.link name="Building's"/>
    </x-breadcrumb.breadcrumb>
    @if ($mode == 'add')
    <div>
        <x-card-header heading="Add Building">
            <x-back-btn wire:click="setmode('all')" />
        </x-card-header>
        <x-form wire:submit="add()">
            @include('livewire.user.building.building-form')
        </x-form>
    </div>
    @elseif($mode=='edit')
    <div>
        <x-card-header heading="Edit Building">
            <x-back-btn wire:click="setmode('all')" />
        </x-card-header>
        <x-form wire:submit="update({{ $building_id }})">
            @include('livewire.user.building.building-form')
        </x-form>
    </div>
    @elseif($mode == 'all')
    <div>
        <x-card-header heading="All Building's">
            <x-add-btn wire:click="setmode('add')" />
        </x-card-header>
        <x-table.frame>
            <x-slot:body>
                <x-table.table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                            <x-table.th wire:click="sort_column('building_name')" name="building_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Building</x-table.th>
                            <x-table.th wire:click="sort_column('Priority')" name="Priority" :sort="$sortColumn" :sort_by="$sortColumnBy">Priority</x-table.th>
                            <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                            <x-table.th> Action </x-table.th>
                        </x-table.tr>
                    </x-table.thead>
                    <x-table.tbody>
                        @foreach ($buildings as $building)
                        <x-table.tr wire:key="{{ $building->id }}">
                            <x-table.td>{{ $building->id }} </x-table.td>
                            <x-table.td>{{ $building->building_name }} </x-table.td>
                            <x-table.td>{{ $building->Priority }} </x-table.td>
                            <x-table.td>
                             @if ($building->deleted_at)
                                @elseif($building->status==1)
                                        <x-table.active wire:click="update_status({{ $building->id }})" />
                                        @else
                                        <x-table.inactive wire:click="update_status({{ $building->id }})" />
                                        @endif                                     
                            </x-table.td>
                            <x-table.td>
                                        @if ($building->deleted_at)
                                        <x-table.delete wire:click="deleteconfirmation({{ $building->id }})" />
                                        <x-table.restore wire:click="restore({{ $building->id }})" />
                                        @else
                                        <x-table.edit wire:click="edit({{ $building->id }})" />
                                        <x-table.archive wire:click="delete({{ $building->id }})" />
                                        @endif
                                       
                                    </x-table.td>
                        </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table.table>
                </x-slot>
                <x-slot:footer>
                    <x-table.paginate :data="$buildings" />
                    </x-slot>
        </x-table.frame>
    </div>
    @endif
    </div>
