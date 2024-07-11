<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Strong Room's" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Strong Room's" />
  <div>
    @if (!$papersubmissions->isEmpty())
    <div x-data>
      <x-form wire:submit='approve_papaer_set()'>
        <x-table.frame a="0">
          <x-slot:body>
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th>ID</x-table.th>
                  <x-table.th>Subject</x-table.th>
                  @foreach ($pappersets as $set)
                    <x-table.th>
                      {{-- <x-input-checkbox   x-on:change="toggleCheckbox($event, 'set_id_{{ $set->id }}')"  class="h-6 w-6 mx-2" /> --}}
                     SET- {{ $set->set_name }}
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
                        $question_bank = $papersubmission
                            ->questionbanks()
                            ->where('set_id', $set->id)
                            ->first();
                      @endphp
                      @if (isset($question_bank->set_id) && $question_bank->is_used == 0)
                        <x-table.td>
                          <x-input-checkbox name="question_bank.{{ $question_bank->id }}"   wire:model="question_bank.{{ $question_bank->id }}" id="set_id_{{ $set->id }}" class="h-6 w-6 mx-2" />
                        </x-table.td>
                      @elseif(isset($question_bank->is_used))
                        <x-table.td>
                          <x-status type="success" class="!p-0.5 ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                          </x-status>
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
              <span class="h-5 mt-5 mx-2">Used Set</span>
              <x-status type="danger" class="!p-0.5 ml-2 h-5 mt-5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
              </x-status>
              <span class="h-5 mt-5 mx-2">Not Uploaded</span>
              <x-form-btn onclick="return confirm('Are You Sure You Want To Procced');">Use Selected Paper Set For {{ isset($exam->exam_name) ? $exam->exam_name : '' }} Exam</x-form-btn>
            </div>
          </x-slot>
          <x-slot:footer>
            <x-table.paginate :data="$papersubmissions" />
          </x-slot>
        </x-table.frame>
      </x-form>
    </div>
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

@push('scripts')
<script>
  function toggleCheckbox(event, setId) {
        const isChecked = event.target.checked;
        const checkboxes = document.querySelectorAll('#'+setId);
        checkboxes.forEach(function(checkbox) {
          checkbox.checked = isChecked;
        });
    }
</script>
@endpush
