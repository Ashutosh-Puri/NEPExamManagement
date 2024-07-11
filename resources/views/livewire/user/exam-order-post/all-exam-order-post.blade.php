<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Exam Order Post's" />
  </x-breadcrumb.breadcrumb>
  @if ($mode == 'add')
    <div>
      <x-card-header heading=" Add Exam Order Post">

        <x-back-btn wire:click="setmode('all')" />

      </x-card-header>
      <x-form wire:submit="add()">
        @include('livewire.user.exam-order-post.exam-order-post-form')
      </x-form>
    </div>
  @elseif($mode == 'edit')
    <x-card-header heading="Edit Exam Order Post">
      <x-back-btn wire:click="setmode('all')" />
    </x-card-header>
    <x-form wire:submit="update({{ $post_id }})">
      @include('livewire.user.exam-order-post.exam-order-post-form')
    </x-form>
  @elseif($mode == 'all')
    <div>
      <x-card-header heading="All Exam Order Post's">
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
                <x-table.th wire:click="sort_column('post_name')" name="post_name" :sort="$sortColumn" :sort_by="$sortColumnBy">Post Name </x-table.th>
                <x-table.th wire:click="sort_column('status')" name="status" :sort="$sortColumn" :sort_by="$sortColumnBy">Status </x-table.th>
                <x-table.th> Action </x-table.th>
              </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
              @forelse ($Posts as $post)
                <x-table.tr wire:key="{{ $post->id }}">
                  <x-table.td> {{ $post->id }}</x-table.td>
                  <x-table.td> {{ $post->post_name }} </x-table.td>
                  <x-table.td>
                    @if ($post->deleted_at)
                    @elseif($post->status == 1)
                      <x-table.active wire:click="update_status({{ $post->id }})" />
                    @else
                      <x-table.inactive wire:click="update_status({{ $post->id }})" />
                    @endif
                  </x-table.td>
                  <x-table.td>
                    @if ($post->deleted_at)
                      <x-table.delete wire:click="deleteconfirmation({{ $post->id }})" />
                      <x-table.restore wire:click="restore({{ $post->id }})" />
                    @else
                      <x-table.edit wire:click="edit({{ $post->id }})" />
                      <x-table.archive wire:click="delete({{ $post->id }})" />
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
          <x-table.paginate :data="$Posts" />
        </x-slot>
      </x-table.frame>
    </div>
  @endif
</div>
