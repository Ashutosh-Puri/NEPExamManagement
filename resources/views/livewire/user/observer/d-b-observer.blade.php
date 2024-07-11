<div>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
          DB Observer <x-status type="danger" wire:click='truncate()'>Truncate</x-status> <x-spinner/>
        </div>
    <div  wire:poll.5000ms>
        <x-table.table >
            <x-table.thead>
                <x-table.tr>
                    <x-table.th>Table Name</x-table.th>
                    <x-table.th>Row ID</x-table.th>
                    <x-table.th>Column Name</x-table.th>
                    <x-table.th>Old Value</x-table.th>
                    <x-table.th>New Value</x-table.th>
                    <x-table.th>Operation</x-table.th>
                    <x-table.th>Timestamp</x-table.th>
                </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
                @foreach($logs as $log)
                    <x-table.tr>
                        <x-table.td>{{ $log->table_name }}</x-table.td>
                        <x-table.td>{{ $log->row_id }}</x-table.td>
                        <x-table.td>{{ $log->column_name }}</x-table.td>
                        <x-table.td>{{ $log->old_value }}</x-table.td>
                        <x-table.td>{{ $log->new_value }}</x-table.td>
                        <x-table.td>{{ $log->operation  }}</x-table.td>
                        <x-table.td>{{ $log->created_at }}</x-table.td>
                    </x-table.tr>
                @endforeach
            </x-table.tbody>
        </x-table.table>
        {{ $logs->links() }}
    </div>
    </div>
</div>
