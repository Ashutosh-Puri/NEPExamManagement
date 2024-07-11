<div>
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-pght">
      Site Setting
    </div>
    <div>
      <x-full-spinner />
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th> Section </x-table.th>
            <x-table.th> Setting Name </x-table.th>
            <x-table.th> Value </x-table.th>
            <x-table.th> Action </x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          <x-table.tr>
            <x-table.td>Exam Form </x-table.td>
            <x-table.td>ABC ID </x-table.td>
            <x-table.td>
              @if ($show_abcid)
                Visible
              @else
                Hidden
              @endif
            </x-table.td>
            <x-table.td>
              <x-toggle wire:click="toggleValue('show_abcid')" wire:target='show_abcid' name="show_abcid" id="show_abcid" wire:model='show_abcid' value="{{ old('show_abcid', $show_abcid) }}" />
            </x-table.td>
          </x-table.tr>
          <x-table.tr>
            <x-table.td>Exam Form </x-table.td>
            <x-table.td>ABC ID </x-table.td>
            <x-table.td>
              @if ($abcid_required)
                Required
              @else
                Optional
              @endif
            </x-table.td>
            <x-table.td>
              <x-toggle wire:click="toggleValue('abcid_required')" wire:target='abcid_required' name="abcid_required" id="abcid_required" wire:model='abcid_required' value="{{ old('abcid_required', $abcid_required) }}" />
            </x-table.td>
          </x-table.tr>
          <x-table.tr>
            <x-table.td>Exam Form Fee</x-table.td>
            <x-table.td>Statement Of Marks Fee</x-table.td>
            <x-table.td>
              @if ($statement_of_marks_is_year_wise)
                Class Wise
              @else
                SEM Wise
              @endif
            </x-table.td>
            <x-table.td>
              <x-toggle wire:click="toggleValue('statement_of_marks_is_year_wise')" wire:target='statement_of_marks_is_year_wise' name="statement_of_marks_is_year_wise" id="statement_of_marks_is_year_wise" wire:model='statement_of_marks_is_year_wise' value="{{ old('statement_of_marks_is_year_wise', $statement_of_marks_is_year_wise) }}" />
            </x-table.td>
          </x-table.tr>
          <x-table.tr>
            <x-table.td>Question Paper</x-table.td>
            <x-table.td>Apply Watermark While Downloding </x-table.td>
            <x-table.td>
              @if ($question_paper_apply_watermark)
                Yes
              @else
                No
              @endif
            </x-table.td>
            <x-table.td>
              <x-toggle wire:click="toggleValue('question_paper_apply_watermark')" wire:target='question_paper_apply_watermark' name="question_paper_apply_watermark" id="question_paper_apply_watermark" wire:model='question_paper_apply_watermark' value="{{ old('question_paper_apply_watermark', $question_paper_apply_watermark) }}" />
            </x-table.td>
          </x-table.tr>
          <x-table.tr>
            <x-table.td>Question Paper </x-table.td>
            <x-table.td>Question Paper Master Password </x-table.td>
            <x-table.td>{{ $question_paper_pdf_master_password }} </x-table.td>
            <x-table.td>
              <form wire:submit="updateValue('question_paper_pdf_master_password')">
                <x-text-input name="question_paper_pdf_master_password" wire:target='question_paper_pdf_master_password' id="question_paper_pdf_master_password" wire:model='question_paper_pdf_master_password' value="{{ old('question_paper_pdf_master_password', $question_paper_pdf_master_password) }}" />
                <x-table.edit type="submit" i='0' class="p-2"> Update</x-table.edit>
              </form>
            </x-table.td>
          </x-table.tr>
          <x-table.tr>
            <x-table.td>Strong Room </x-table.td>
            <x-table.td>Time Before Exam Start in Minutes </x-table.td>
            <x-table.td>{{ $exam_time_interval }} Minutes. </x-table.td>
            <x-table.td>
              <form wire:submit="updateValue('exam_time_interval')">
                <x-text-input name="exam_time_interval" wire:target='exam_time_interval' id="exam_time_interval" wire:model='exam_time_interval' value="{{ old('exam_time_interval', $exam_time_interval) }}" />
                <x-table.edit type="submit" i='0' class="p-2"> Update</x-table.edit>
              </form>
            </x-table.td>
          </x-table.tr>
          <x-table.tr>
            <x-table.td>Storage</x-table.td>
            <x-table.td>Storage Link</x-table.td>
            <x-table.td>
              @if ($storage_link)
                Symbolic Link Found
              @else
                Symbolic Link Not Found
              @endif
            </x-table.td>
            <x-table.td>
              <x-table.restore wire:click="deleteSymbolicLinkAndCreateNew()"> Regenerate Symbolic Link</x-table.restore>
            </x-table.td>
          </x-table.tr>
          <x-table.tr>
            <x-table.td>Laravel Log</x-table.td>
            <x-table.td>Log File</x-table.td>
            <x-table.td>
              @if ($laravel_log)
                Logs Found
              @else
                Logs Not Found
              @endif
            </x-table.td>
            <x-table.td>
              <x-table.download wire:click="downloadLogFile()">Download Log File</x-table.download>
              <x-table.cancel wire:click="clearLogFile()">Clear Log File</x-table.cancel>
            </x-table.td>
          </x-table.tr>
          <x-table.tr>
            <x-table.td>Laravel Cache</x-table.td>
            <x-table.td>
              cache ,
              optimize ,
              event ,
              config ,
              auth ,
              view ,
              queue 
            </x-table.td>
            <x-table.td>
              Unknown
            </x-table.td>
            <x-table.td>
              <x-table.cancel wire:click="clearCache()">Clear All Cache</x-table.cancel>
            </x-table.td>
          </x-table.tr>
        </x-table.tbody>
      </x-table.table>
    </div>
  </div>
</div>
