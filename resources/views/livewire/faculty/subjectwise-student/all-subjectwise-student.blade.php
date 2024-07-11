<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Subjectwise Student" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'all')
    <div>
      <x-card-header heading="Subjectwise Student Exam Summary" />
      <x-table.frame x="0" r="0">
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('subject_sem')" name="subject_sem" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Sem</x-table.th>
                <x-table.th wire:click="sort_column('subject_code_name')" name="subject_code_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Code & Name</x-table.th>
                <x-table.th wire:click="sort_column('student_count')" name="student_count" :sort="$sortColumn" :sort_by="$sortColumnBy">Students</x-table.th>
                <x-table.th>Action</x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($subjects_with_student_counts as $subject_with_student_count)
                <x-table.tr wire:key="{{ $subject_with_student_count->id }}">
                  <x-table.td> {{ $subject_with_student_count->id }} </x-table.td>
                  <x-table.td> {{ $subject_with_student_count->subject_sem }} </x-table.td>
                  <x-table.td>
                    {{ $subject_with_student_count->subject_code }} <br>
                    {{ $subject_with_student_count->subject_name }}
                  </x-table.td>
                  <x-table.td> {{ $subject_with_student_count->student_examforms_count }} </x-table.td>
                  <x-table.td>
                    @if ($subject_with_student_count->student_examforms_count > 1)
                      <form method="post" action="{{ route('faculty.download_subjectwise_student_report') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="subject_report_id" value="{{ $subject_with_student_count->id }}">
                        <x-table.edit type="submit" i="0">Download PDF</x-table.edit>
                      </form>
                      <form method="post" action="{{ route('faculty.download_subjectwise_student_excel_report') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="subject_report_id" value="{{ $subject_with_student_count->id }}">
                        <x-table.edit type="submit" i="0">EXCEL</x-table.edit>
                      </form>
                    @else
                      <p>
                        <x-table.edit class="bg-slate-500" i="0">Download PDF</x-table.edit>
                        <x-table.edit class="bg-slate-500" i="0">EXCEL</x-table.edit>
                      </p>
                    @endif
                  </x-table.td>
                </x-table.tr>
              @empty
                <x-table.tr>
                  <x-table.td colspan='5' class="text-center">No Data Found</x-table.td>
                </x-table.tr>
              @endforelse
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$subjects_with_student_counts" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
