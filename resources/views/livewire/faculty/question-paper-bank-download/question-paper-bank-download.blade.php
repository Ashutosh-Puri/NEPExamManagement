<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Question Paper Download's" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Question Paper Download's" />
  <div>
    @if (!$papersubmissions->isEmpty())
        <x-table.frame a="0">
          <x-slot:body>
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th>ID</x-table.th>
                  <x-table.th>Subject</x-table.th>
                  @foreach ($pappersets as $set)
                    <x-table.th>
                      SET-{{ $set->set_name }}
                    </x-table.th>
                  @endforeach
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @foreach ($papersubmissions as $papersubmission)
                  <x-table.tr wire:key="{{ $papersubmission->id }}">
                    <x-table.td> {{ $papersubmission->id }}</x-table.td>
                    <x-table.td> {{ $papersubmission->subject->subject_name }}</x-table.td>
                    @foreach ($pappersets as $set)
                      @php
                        $question_bank = $papersubmission->questionbanks()->where('set_id', $set->id)->first();
                      @endphp
                      @if (isset($question_bank->set_id) && $question_bank->is_used == 0)
                        <x-table.td>
                          <x-status type="success" class="!p-0.5 ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                          </x-status>
                        </x-table.td>
                      @elseif(isset($question_bank->is_used))
                        <x-table.td>
                          <form id="download" name="download" class="inline" action="{{ route('faculty.download_question_paper') }}" method="post" target="_blank" >
                            @csrf
                            <input type="hidden" name="questionpaperbank" id="questionpaperbank" value="{{ $question_bank->id }}">
                            <x-table.download type="submit">Download</x-table.download>
                          </form>
                        </x-table.td>
                      @else
                        <x-table.td>
                          <x-status type="danger" class="!p-0.5 ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                          </x-status>
                        </x-table.td>
                      @endif
                    @endforeach
                  </x-table.tr>
                @endforeach
              </x-table.tbody>
            </x-table.table>
            <div class="inline w-full ">
              <x-status type="success" class="!p-0.5 ml-2 h-5 mt-5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
              </x-status>
              <span class="h-5 mt-5 mx-2">Not Used Set</span>
              <x-status type="danger" class="!p-0.5 ml-2 h-5 mt-5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
              </x-status>
              <span class="h-5 mt-5 mx-2">Not Uploaded</span>
            </div>
          </x-slot>
          <x-slot:footer>
            <x-table.paginate :data="$papersubmissions" />
          </x-slot>
        </x-table.frame>
      @php
        session()->forget('session-alert');
      @endphp
    @else
      @php
        session()->flash('session-alert', [['type' => 'info', 'message' => 'Exam Question Paper will be available two hours before the start time.']]);
      @endphp
      <x-alert />
    @endif
  </div>
</div>
