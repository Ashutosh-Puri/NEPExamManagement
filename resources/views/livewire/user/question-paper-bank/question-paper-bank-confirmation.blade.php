<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Confirm Question Paper Bank's" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Confirm Question Paper Bank's" />
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Filter Question Paper Bank's
    </div>
    <div class="overflow-x-scroll">
      <div class="grid grid-cols-12 md:grid-cols-12">
        <div class="px-5 py-2  col-span-5 text-sm text-gray-600 dark:text-gray-400">
          <x-input-select id="faculty_id" wire:model.live="faculty_id" name="faculty_id" class="text-center w-full mt-1" :value="old('faculty_id', $faculty_id)" autocomplete="faculty_id">
            <x-select-option class="text-start" hidden> -- Select Faculty -- </x-select-option>
            @forelse ($faculties as $facultyid =>$facultyname)
              <x-select-option wire:key="{{ $facultyid }}" value="{{ $facultyid }}" class="text-start"> {{ $facultyname ?? '-' }} </x-select-option>
            @empty
              <x-select-option class="text-start">Faculties Not Found</x-select-option>
            @endforelse
          </x-input-select>
          <x-input-error :messages="$errors->get('faculty_id')" class="mt-1" />
        </div>
        <div class="px-5 py-2 col-span-6 text-sm text-gray-600 dark:text-gray-400">
          <x-input-select id="subject_id" wire:model.live="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" autocomplete="subject_id">
            <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
            @forelse ($subjects as $subjectid => $subjectname)
              <x-select-option wire:key="{{ $subjectid }}" value="{{ $subjectid }}" class="text-start"> {{ $subjectname }} </x-select-option>
            @empty
              <x-select-option class="text-start">Subjects Not Found</x-select-option>
            @endforelse
          </x-input-select>
          <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
        </div>
        <div class="col-span-1  mt-3.5">
          <x-table.cancel wire:click="reset_input()"></x-table.cancel>
        </div>
      </div>
    </div>
  </div>
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Confirmed Question Paper Bank's
      <span class="float-end flex">
        <x-status type="danger" class="py-1" wire:click='reopen_all()' i="0">Open All</x-status>
      </span>
    </div>
    <div class="overflow-x-scroll">
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th>ID</x-table.th>
            <x-table.th>Faculty</x-table.th>
            <x-table.th>Subject</x-table.th>
            <x-table.th>Submited Sets</x-table.th>
            <x-table.th>Set Names</x-table.th>
            <x-table.th>Action</x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @forelse ($papersubmissions_1 as  $papersubmission_1)
            <x-table.tr wire:key="{{ $papersubmission_1->id }}">
              <x-table.td>{{ $papersubmission_1->subject->id }} </x-table.td>
              <x-table.td>{{ $papersubmission_1->faculty->faculty_name }} </x-table.td>
              <x-table.td>{{ $papersubmission_1->subject->subject_name }} </x-table.td>
              <x-table.td>{{ $papersubmission_1->noofsets }} </x-table.td>
              <x-table.td>
                @foreach ($papersubmission_1->questionbanks()->get() as $j => $s)
                  @if ($j)
                    ,
                  @endif {{ $s->paperset->set_name }}
                @endforeach
              </x-table.td>
              <x-table.td> <x-table.delete i="0" wire:click='reopen_one({{ $papersubmission_1->id }})'>Open</x-table.delete> </x-table.td>
            </x-table.tr>
          @empty
            <x-table.tr>
              <x-table.td colspan='4' class="text-center">No Data Found</x-table.td>
            </x-table.tr>
          @endforelse
        </x-table.tbody>
      </x-table.table>
    </div>
  </div>
  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Not Confirmed Question Paper Bank's
      <span class="float-end flex">
        <x-status type="success" class="py-1" wire:click='close_all()' i="0">Close All</x-status>
      </span>
    </div>
    <div class="overflow-x-scroll">
      <x-table.table>
        <x-table.thead>
          <x-table.tr>
            <x-table.th>ID</x-table.th>
            <x-table.th>Subject</x-table.th>
            <x-table.th>Faculty</x-table.th>
            <x-table.th>Submited Sets</x-table.th>
            <x-table.th>Set Names</x-table.th>
            <x-table.th>Action</x-table.th>
          </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
          @forelse ($papersubmissions_0 as  $papersubmission_0)
            <x-table.tr wire:key="{{ $papersubmission_0->id }}">
              <x-table.td>{{ $papersubmission_0->subject->id }} </x-table.td>
              <x-table.td>{{ $papersubmission_0->faculty->faculty_name }} </x-table.td>
              <x-table.td>{{ $papersubmission_0->subject->subject_name }} </x-table.td>
              <x-table.td>{{ $papersubmission_0->noofsets }} </x-table.td>
              <x-table.td>
                @foreach ($papersubmission_0->questionbanks()->get() as $k => $ss)
                  @if ($k)
                    ,
                  @endif {{ $ss->paperset->set_name }}
                @endforeach
              </x-table.td>
              <x-table.td> <x-table.approve wire:click='close_one({{ $papersubmission_0->id }})' i="0">Close</x-table.approve> </x-table.td>
            </x-table.tr>
          @empty
            <x-table.tr>
              <x-table.td colspan='4' class="text-center">No Data Found</x-table.td>
            </x-table.tr>
          @endforelse
        </x-table.tbody>
      </x-table.table>
    </div>
  </div>
</div>
