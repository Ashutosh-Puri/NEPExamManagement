<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Order's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Exam Order">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.exam-order.exam-order-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading=" All Exam Order's">
      </x-card-header>
      <x-table.frame>
        <x-slot:header>
          <div class="flex gap-x-0.5">
            <x-status class='mx-1 h-8.5' type="info" i="0" wire:click="bulk_resend_exam_order_mail()"> All Resend </x-status>
            <x-status class='mx-1 h-8.5' type="info" i="0" wire:click="bulk_cancel_exam_order()"> All Cancel </x-status>
            <x-status class='mx-1 h-8.5' type="danger" i="0" wire:click="bulk_delete_exam_order()"> All Delete </x-status>
          </div>
        </x-slot>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">No.</x-table.th>
                <x-table.th wire:click="sort_column('exampanel_id')" name="exampanel_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam Panel </x-table.th>
                <x-table.th wire:click="sort_column('exam_patternclass_id')" name="exam_patternclass_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Exam Pattern Class</x-table.th>
                {{-- <x-table.th wire:click="sort_column('description')" name="description" :sort="$sortColumn" :sort_by="$sortColumnBy"> Description</x-table.th> --}}
                <x-table.th wire:click="sort_column('email_status')" name="email_status" :sort="$sortColumn" :sort_by="$sortColumnBy">Email Send</x-table.th>
                <x-table.th> Action</x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($examorders as $examorder)
                <x-table.tr wire:key="{{ $examorder->id }}">
                  <x-table.td> {{ $examorder->id }} </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll class="w-full"> {{ isset($examorder->exampanel->faculty->faculty_name) ? $examorder->exampanel->faculty->faculty_name : '-' }} </x-table.text-scroll>
                  </x-table.td>
                  <x-table.td>
                    <x-table.text-scroll class="w-full">{{ isset($examorder->exampatternclass->exam->exam_name) ? $examorder->exampatternclass->exam->exam_name : '-' }} {{ isset($examorder->exampatternclass->patternclass->pattern->pattern_name) ? $examorder->exampatternclass->patternclass->pattern->pattern_name : '-' }} {{ isset($examorder->exampatternclass->patternclass->courseclass->classyear->classyear_name) ? $examorder->exampatternclass->patternclass->courseclass->classyear->classyear_name : '-' }} {{ isset($examorder->exampatternclass->patternclass->courseclass->course->course_name) ? $examorder->exampatternclass->patternclass->courseclass->course->course_name : '-' }} </x-table.text-scroll>
                  </x-table.td>
                  {{-- <x-table.td> {{ $examorder->description }} </x-table.td> --}}
                  <x-table.td>
                    @if ($examorder->deleted_at)
                    @elseif($examorder->email_status == 1)
                      <x-status type="success">Yes</x-table.status>
                      @else
                        <x-status type="danger"> No </x-table.status>
                    @endif

                  </x-table.td>
                  <x-table.td>
                    @if ($examorder->email_status == 0)
                      <x-table.delete wire:click="deleteconfirmation({{ $examorder->id }})" />
                    @else
                      <x-table.regenerate  wire:click="resend_exam_order_mail({{ $examorder->id }})">Resend </x-table.regenerate>
                      <x-table.cancel   wire:click="cancel_exam_order({{ $examorder->id }})">Cancel  </x-table.cancel>
                      {{-- <x-table.merge   wire:click="merge_exam_order_mail({{ $examorder->id }})">Merge</x-table.merge> --}}
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
          <x-table.paginate :data="$examorders" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
