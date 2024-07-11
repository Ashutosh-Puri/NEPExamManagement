<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Pending External Mark's" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Pending External Mark's">
    <a wire:navigate href="{{ route('user.external_marks_entry_verify') }}"><x-back-btn /></a>
  </x-card-header>
  <div class="overflow-x-scroll mt-2">
    <x-table.table>
      <x-table.thead>
        <x-table.tr>
          <x-table.th> Sr.No </x-table.th>
          <x-table.th> Class </x-table.th>
          <x-table.th> Subject </x-table.th>
          <x-table.th> Lot Number </x-table.th>
          <x-table.th> No.Of papers </x-table.th>
          <x-table.th> Moderator </x-table.th>
          <x-table.th> Examiner </x-table.th>
        </x-table.tr>
      </x-table.thead>
      <x-table.tbody>
        @php
          $index = $paperassesments->firstItem() ?? 0;
        @endphp
        @foreach ($paperassesments as $paperassesment)
          <x-table.tr wire:key="{{ $paperassesment->id }}">
            <x-table.td>{{ $index++ }} </x-table.td>
            <x-table.td>{{ $paperassesment->subject->patternclass->courseclass->classyear->classyear_name }} {{ $paperassesment->subject->patternclass->courseclass->course->course_name }} {{ $paperassesment->subject->patternclass->pattern->pattern_name }}</x-table.td>
            <x-table.td>{{ $paperassesment->subject->subject_code }} {{ $paperassesment->subject->subject_name }}</x-table.td>
            <x-table.td>{{ $paperassesment->id }}</x-table.td>
            <x-table.td>{{ $paperassesment->exambarcodes_count }}</x-table.td>
            <x-table.td>{{ $paperassesment->moderator->faculty_name ?? '' }}</x-table.td>
            <x-table.td>{{ $paperassesment->examiner->faculty_name ?? '' }}</x-table.td>
          </x-table.tr>
        @endforeach
      </x-table.tbody>
    </x-table.table>
    <x-table.paginate :data="$paperassesments" />
  </div>
</div>
