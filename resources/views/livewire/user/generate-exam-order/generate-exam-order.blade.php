<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Order's" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Generate Exam Order's" />
  <x-table.frame x='0' r='0'>
    <x-slot:header>
      <div class="flex gap-0.5">
        <span class="mx-2 mt-2">Select Semesters :</span>
        <x-select2.select multiple="multiple" style="width:100%;" id="semester" name="semester" wire:model.live='semester' class="rounded-lg h-10 !w-full" >
          @foreach ($semesters as $semid2 => $semester2)
            <x-select2.option value="{{ $semid2 }}">{{ $semester2 }}</x-select2.option>
          @endforeach
        </x-select2.select>
      </div>
    </x-slot>
    <x-slot:body>
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">No.</x-table.th>
            <x-table.th wire:click="sort_column('exam_name')" name="exam_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam Name </x-table.th>
            <x-table.th wire:click="sort_column('patternclass_id')" name="patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Pattern Class Name </x-table.th>
            <x-table.th> Action </x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @forelse ($exampatternclasses as $exampatternclass)
            <x-table.tr wire:key="{{ $exampatternclass->id }}">
              <x-table.td> {{ $exampatternclass->id }}</x-table.td>
              <x-table.td>
                <x-table.text-scroll> {{ $exampatternclass->exam->exam_name }} </x-table.text-scroll>
              </x-table.td>
              <x-table.td>
                <x-table.text-scroll class="w-full"> {{ isset($exampatternclass->patternclass->pattern->pattern_name) ? $exampatternclass->patternclass->courseclass->classyear->classyear_name : '-' }} {{ isset($exampatternclass->patternclass->courseclass->course->course_name) ? $exampatternclass->patternclass->courseclass->course->course_name : '-' }} {{ isset($exampatternclass->patternclass->courseclass->course->course_name) ? $exampatternclass->patternclass->pattern->pattern_name : '-' }}</x-table.text-scroll>
              </x-table.td>
              <x-table.td>
                @if ($exampatternclass->is_order)
                  <x-table.create wire:click="generate_exam_order({{ $exampatternclass->id }})" />
                @else
                  <x-table.sendmail wire:click="send_mail({{ $exampatternclass->id }})" />
                  {{-- <x-table.cancel class="px-2.5" wire:click="cancel_exam_order({{ $exampatternclass->id }})" /> --}}
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
      <x-table.paginate :data="$exampatternclasses" />
    </x-slot>
  </x-table.frame>
</div>
