<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Fee's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading="Add Exam Fee">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.exam-fee.exam-fee-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <div>
      <x-card-header heading="Edit Exam Fee">
        <x-back-btn wire:click="setmode('all')" />
      </x-card-header>
      <x-form wire:submit="update({{ $edit_id }})">
        @include('livewire.user.exam-fee.exam-fee-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
     
      <x-card-header heading="All Exam Fee's">
        <x-add-btn wire:click="setmode('add')" />
      </x-card-header>
      <x-table.frame>
        <x-slot:body>
          <x-table.table>
            <x-table.thead>
              <x-table.tr>
                <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">ID</x-table.th>
                <x-table.th wire:click="sort_column('fee_name')" name="fee_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Fee Name</x-table.th>
                <x-table.th wire:click="sort_column('default_professional_fee')" name="default_professional_fee" :sort="$sortColumn" :sort_by="$sortColumnBy">Professional Fee</x-table.th>
                <x-table.th wire:click="sort_column('default_non_professional_fee')" name="default_non_professional_fee" :sort="$sortColumn" :sort_by="$sortColumnBy">Non Professional Fee</x-table.th>
                <x-table.th wire:click="sort_column('form_type_id')" name="form_type_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Form Type</x-table.th>
                <x-table.th wire:click="sort_column('apply_fee_id')" name="apply_fee_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Apply Fee</x-table.th>
                <x-table.th wire:click="sort_column('remark')" name="remark" :sort="$sortColumn" :sort_by="$sortColumnBy">Remark</x-table.th>
                <x-table.th wire:click="sort_column('approve_status')" name="approve_status" :sort="$sortColumn" :sort_by="$sortColumnBy">Approved</x-table.th>
                <x-table.th wire:click="sort_column('active_status')" name="active_status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status</x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @foreach ($exam_fee_masters as $exam_fee_master)
                <x-table.tr wire:key="{{ $exam_fee_master->id }}">
                  <x-table.td>{{ $exam_fee_master->id }} </x-table.td>
                  <x-table.td> <x-table.text-scroll> {{ $exam_fee_master->fee_name }} </x-table.text-scroll></x-table.td>
                  <x-table.td>{{ isset($exam_fee_master->default_professional_fee) ? $exam_fee_master->default_professional_fee : '-' }} </x-table.td>
                  <x-table.td>{{ isset($exam_fee_master->default_non_professional_fee) ? $exam_fee_master->default_non_professional_fee : '-' }} </x-table.td>
                  <x-table.td><x-table.text-scroll> {{ isset($exam_fee_master->formtype->form_name) ? $exam_fee_master->formtype->form_name : '-' }} </x-table.text-scroll></x-table.td>
                  <x-table.td> <x-table.text-scroll> {{ isset($exam_fee_master->applyfee->name) ? $exam_fee_master->applyfee->name : '-' }} </x-table.text-scroll></x-table.td>
                  <x-table.td><x-table.text-scroll>{{ $exam_fee_master->remark }} </x-table.text-scroll> </x-table.td>
                  <x-table.td>
                    @if ($exam_fee_master->approve_status == 1)
                      <x-status type="success"> Yes </x-status>
                    @else
                      <x-status type="danger"> No </x-status>
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if (!$exam_fee_master->deleted_at)
                      @if ($exam_fee_master->active_status)
                        <x-table.active wire:click="status({{ $exam_fee_master->id }})" />
                      @else
                        <x-table.inactive wire:click="status({{ $exam_fee_master->id }})" />
                      @endif
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($exam_fee_master->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $exam_fee_master->id }})" />
                      <x-table.restore wire:click="restore({{ $exam_fee_master->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $exam_fee_master->id }})" />
                      @if ($exam_fee_master->approve_status == 1)
                        <x-table.reject wire:click="approve({{ $exam_fee_master->id }})" />
                      @else
                        <x-table.approve wire:click="approve({{ $exam_fee_master->id }})" />
                      @endif
                      <x-table.archive wire:click="delete({{ $exam_fee_master->id }})" />
                    @endif
                  </x-table.td>
                </x-table.tr>
              @endforeach
            </x-table.tbody>
          </x-table.table>
        </x-slot>
        <x-slot:footer>
          <x-table.paginate :data="$exam_fee_masters" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
