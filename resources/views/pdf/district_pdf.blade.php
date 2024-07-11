<table width="100%" border=1>
  <thead>
    <tr>
      <th width="10%">ID</th>
      <th width="30%">District Code</th>
      <th width="30%">District Name</th>
      <th width="30%">State Name</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($districts as $district)
      <tr>
        <td  width="10%">{{ $district->id }}</td>
        <td  width="30%">{{ $district->district_code }}</td>
        <td  width="30%">{{ $district->district_name }}</td>
        <td  width="30%">{{ $district->state_name }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
