<x-table.table>
    <x-table.thead>
      <x-table.tr>
        <x-table.th>No.</x-table.th>
        <x-table.th>Academic Year</x-table.th>
        <x-table.th>Member ID</x-table.th>
        <x-table.th>Student</x-table.th>
        <x-table.th>Subject </x-table.th>
        <x-table.th>Pattern Class</x-table.th>
        <x-table.th>Action</x-table.th>
      </x-table.tr>
    </x-table.thead>
    <x-table.tbody>
      @for ($z=1; $z<=10 ; $z++)
        <x-table.tr>
          <x-table.td> <x-flash.text/></x-table.td>
          <x-table.td> <x-flash.text/></x-table.td>
          <x-table.td> <x-flash.text/></x-table.td>
          <x-table.td> <x-flash.text/></x-table.td>
          <x-table.td> <x-flash.text/></x-table.td>
          <x-table.td> <x-flash.text/></x-table.td>
          <x-table.td> <x-flash.status/></x-table.td>
          <x-table.td> <x-flash.button/> <x-flash.button/></x-table.td>
        </x-table.tr>
      @endfor
    </x-table.tbody>
  </x-table.table>