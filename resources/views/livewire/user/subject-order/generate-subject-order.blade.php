<div>

  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Subject Order ">
      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.subject-order.subject-order-form')
      </x-form>
    </div>
  @elseif($mode == 'all')
    <div>
      <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Order's" />
        </x-breadcrumb.link>
        <x-card-header heading="Generate Subject Order's">
          <x-add-btn wire:click="setmode('add')" />
        </x-card-header>
        <x-table.frame>
          <x-slot:header>
          </x-slot>
          <x-slot:body>
            <x-table.table>
              <x-table.thead>
                <x-table.tr>
                  <x-table.th wire:click="sort_column('id')" name="id" :sort="$sortColumn" :sort_by="$sortColumnBy">No.</x-table.th>
                  <x-table.th wire:click="sort_column('faculty_id')" name="faculty_id" :sort="$sortColumn" :sort_by="$sortColumnBy">Faculty Name </x-table.th>
                  <x-table.th wire:click="sort_column('subject_name')" name="subject_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Subject Name </x-table.th>
                  <x-table.th wire:click="sort_column('post_name')" name="post_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Post Name </x-table.th>
                  <x-table.th> Action </x-table.th>
                </x-table.tr>
              </x-table.thead>
              <x-table.tbody>
                @forelse ($panels as $panel)
                  <x-table.tr wire:key="{{ $panel->id }}">
                    <x-table.td> {{ $panel->id }}</x-table.td>
                    <x-table.td>
                      <x-table.text-scroll> {{ $panel->faculty->faculty_name }} </x-table.text-scroll>
                    </x-table.td>
                    <x-table.td>
                      <x-table.text-scroll> {{ $panel->subject->subject_name }} </x-table.text-scroll>
                    </x-table.td>
                    <x-table.td>
                      <x-table.text-scroll> {{ $panel->examorderpost->post_name }} </x-table.text-scroll>
                    </x-table.td>
                    <x-table.td>
                      <x-table.edit i="0" wire:click="generateFacultyOrder({{ $panel->id }})">Create Order</x-table.edit>
                      <x-table.edit i="0" wire:click="SendMail({{ $panel->id }})">Send Mail</x-table.edit></a>
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
            <x-table.paginate :data="$panels" />
          </x-slot>
        </x-table.frame>
    </div>
  @endif
</div>
