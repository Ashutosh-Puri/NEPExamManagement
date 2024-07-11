<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Helpline Request's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Helpline Request">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.helpline.helpline-form')
      </x-form>
    </div>
  @elseif($mode == 'view')
    <div>
      <x-card-header heading="View Helpline Request">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
          View Modification Request
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2">
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="student_id" :value="__('Student Name')" />
            <x-input-show id="student_id" :value="$student_name" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="student_helpline_query_id" :value="__('Selected Query')" />
            <x-input-show id="student_helpline_query_id" :value="$current_query" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="old_query" :value="__('Current Query')" />
            <x-input-show id="old_query" :value="$old_query" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="query" :value="__('New Query')" />
            <x-input-show id="query" :value="$query" />
          </div>
        </div>
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
          <x-input-label for="description" :value="__('Description')" />
          <x-textarea-show id="description" :value="$description" />
        </div>
        @if (count($documents) > 0)
          <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
              Uploaded Documents <x-spinner />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 mx-auto">
              @foreach ($documents as $doc)
                <div class="flex flex-col items-center space-x-1 border rounded-md border-primary m-2 ">
                  <div class="shrink-0 p-2">
                    <img style="width: 135px; height: 150px;" class="object-center object-fill  " src="{{ isset($uploaded_documents_old[$doc->id]) ? asset($uploaded_documents_old[$doc->id]) : asset('img/no-img.png') }}" alt="{{ $doc->document_name }}" />
                  </div>
                  <x-input-label class="py-2" for="{{ $doc->id }}" :value="$doc->document_name" />
                  <x-view-image-model-btn class="mb-2" title="{{ $doc->document_name }}" src="{{ isset($uploaded_documents_old[$doc->id]) ? asset($uploaded_documents_old[$doc->id]) : asset('img/no-img.png') }}"> View Document</x-view-image-model-btn>
                </div>
              @endforeach
            </div>
          </div>
        @endif
        <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
          <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
            Action
          </div>
          <x-form wire:submit="feedback({{ $edit_id }})">
            <div class="grid grid-cols-1 md:grid-cols-3 mx-auto">
              <div class="px-5  py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="remark" :value="__('Remark')" />
                <x-textarea id="remark" rows="2" placeholder="Enter Remark" type="text" wire:model="remark" name="remark" class="w-full mt-1" :value="old('remark', $remark)" autocomplete="remark" />
                <x-input-error :messages="$errors->get('remark')" class="mt-1" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="query" :value="__('Query')" />
                <x-text-input id="query" type="text" wire:model="query" placeholder="{{ __('Query') }}" name="query" class="w-full mt-1" :value="old('query', $query)" autocomplete="query" />
                <x-input-error :messages="$errors->get('query')" class="mt-1" />
              </div>
              <div class="px-5  py-12 text-sm text-gray-600 dark:text-gray-400">
                <x-table.approve wire:click="approve({{ $edit_id }})" >Approve</x-table.approve>
                <x-table.verify wire:click="verify({{ $edit_id }})" >Verify</x-table.verify>
                <x-table.reject wire:click="reject({{ $edit_id }})" >Reject</x-table.reject>
              </div>
            </div>
          </x-form>
        </div>
      </div>
    </div>
  @elseif($mode == 'all')
    <div>
      
        <x-card-header heading="All Helpline Request's">
          <x-add-btn wire:click="setmode('add')" />
        </x-card-header>
        <x-table.frame>
          <x-slot:body>
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                  <x-table.th wire:click="sort_column('student_id')" name="student_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Student</x-table.th>
                  <x-table.th wire:click="sort_column('student_helpline_query_id')" name="student_helpline_query_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Query</x-table.th>
                  <x-table.th wire:click="sort_column('remark')" name="remark" :sort="$sortColumn" :sort_by="$sortColumnBy">Remark</x-table.th>
                  <x-table.th wire:click="sort_column('verified_by')" name="verified_by" :sort="$sortColumn" :sort_by="$sortColumnBy">Verified By</x-table.th>
                  <x-table.th wire:click="sort_column('approve_by')" name="approve_by" :sort="$sortColumn" :sort_by="$sortColumnBy">Approved By</x-table.th>
                  <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                  <x-table.th> Action </x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @foreach ($student_helplines as $helpline)
                  <x-table.tr wire:key="{{ $helpline->id }}">
                    <x-table.td>{{ $helpline->id }} </x-table.td>
                    <x-table.td> <x-table.text-scroll> {{ $helpline->student->student_name }} </x-table.text-scroll></x-table.td>
                    <x-table.td> <x-table.text-scroll> {{ $helpline->studenthelplinequery->query_name }} </x-table.text-scroll> </x-table.td>
                    <x-table.td> <x-table.text-scroll> {{ $helpline->remark ?? '-' }} </x-table.text-scroll></x-table.td>
                    <x-table.td> <x-table.text-scroll> {{ isset($helpline->verified->name) ? $helpline->verified->name : 'N.A.' }} </x-table.text-scroll> </x-table.td>
                    <x-table.td> <x-table.text-scroll> {{ isset($helpline->approved->name) ? $helpline->approved->name : 'N.A.' }} </x-table.text-scroll> </x-table.td>
                    <x-table.td>
                      @if ($helpline->status == 0)
                        <x-status type="warning"> Pending </x-status>
                      @elseif ($helpline->status == 1)
                        <x-status type="info"> Verified </x-status>
                      @elseif ($helpline->status == 2)
                        <x-status type="success"> Approved </x-status>
                      @elseif ($helpline->status == 3)
                        <x-status type="danger"> Canceled </x-status>
                      @elseif ($helpline->status == 4)
                        <x-status type="danger"> Rejected </x-status>
                      @endif
                    </x-table.td>
                    <x-table.td>
                      @if ($helpline->deleted_at)
                        <x-table.delete wire:click="deleteconfirmation({{ $helpline->id }})" />
                        <x-table.restore wire:click="restore({{ $helpline->id }})" />
                      @else
                        <x-table.view wire:click="view({{ $helpline->id }})" />
                          @if ($helpline->status == 0)
                          <x-table.approve wire:click="status({{ $helpline->id }})" />
                        @elseif ($helpline->status == 1)
                          <x-table.reject wire:click="status({{ $helpline->id }})" />
                        @endif
                        {{-- <x-table.edit wire:click="edit({{ $helpline->id }})" /> --}}
                        <x-table.archive wire:click="delete({{ $helpline->id }})" />
                      @endif
                    </x-table.td>
                  </x-table.tr>
                @endforeach
              </x-table.tbody>
            </x-table.table>
          </x-slot>
          <x-slot:footer>
            <x-table.paginate :data="$student_helplines" />
          </x-slot>
        </x-table.frame>
    </div>
  @endif
</div>
