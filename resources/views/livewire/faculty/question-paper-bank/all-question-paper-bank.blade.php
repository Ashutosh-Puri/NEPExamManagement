<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Question Paper Bank's" />
  </x-breadcrumb.breadcrumb>
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Question Paper Bank's <x-spinner />
    </div>
    <div class="overflow-x-scroll">
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
            <x-table.th wire:click="sort_column('subject_name')" name="subject_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject</x-table.th>
            @foreach ($sets as $set)
              <x-table.th>{{ 'Set ' . $set->set_name }} </x-table.th>
            @endforeach
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @forelse ($subjects as  $subject)
            <x-table.tr wire:key="{{ $subject->id }}">
              <x-table.td>{{ $subject->id }} </x-table.td>
              <x-table.td>{{ $subject->subject_name }} </x-table.td>
              @foreach ($sets as $set)
                <x-table.td>
                  @php
                    $ukey = $subject->id . '-' . $set->id;
                  @endphp
                  <livewire:faculty.question-paper-bank.question-paper-cell :key="$ukey" :$set exam_id='{{ $exam->id }}' subject_id='{{ $subject->id }}' />
                </x-table.td>
              @endforeach
            </x-table.tr>
          @empty
            <x-table.tr>
              <x-table.td colspan='{{ count($sets) + 2 }}' class="text-center">No Data Found</x-table.td>
            </x-table.tr>
          @endforelse
          <x-table.tr>
            <x-table.td colspan='{{ count($sets) + 2 }}'>
              @if (count($subjects))
                <form wire:submit="confirm_uploaded_paper_sets()">
                  <x-form-btn onclick="return confirm('Are You Sure You Want Confirm And Submit.')">Confirm & Submit All Sets</x-form-btn>
                </form>
              @endif
            </x-table.td>
          </x-table.tr>
        </x-table.tbody>
      </x-table.table>
    </div>
  </div>
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Confirmed Question Paper Bank's
      <span class="float-end flex">
        <a href="{{ route('faculty.faculty_question_paper_bank_report') }}">
          <x-status type="success" class="py-1 " i="0">Download Report</x-status>
        </a>
      </span>
    </div>
    <div class="overflow-x-scroll">
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th>ID</x-table.th>
            <x-table.th>Subject</x-table.th>
            <x-table.th>Submited Sets</x-table.th>
            <x-table.th>Set Names</x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @forelse ($papersubmissions as  $papersubmission)
            <x-table.tr wire:key="{{ $papersubmission->id }}">
              <x-table.td>{{ $papersubmission->subject->id }} </x-table.td>
              <x-table.td>{{ $papersubmission->subject->subject_name }} </x-table.td>
              <x-table.td>{{ $papersubmission->noofsets }} </x-table.td>
              <x-table.td>
                @foreach ($papersubmission->questionbanks()->get() as $k => $ss)
                  @if ($k)
                    ,
                  @endif {{ $ss->paperset->set_name }}
                @endforeach
              </x-table.td>
            </x-table.tr>
          @empty
            <x-table.tr>
              <x-table.td colspan='8' class="text-center">No Data Found</x-table.td>
            </x-table.tr>
          @endforelse
        </x-table.tbody>
      </x-table.table>
    </div>
  </div>
</div>
