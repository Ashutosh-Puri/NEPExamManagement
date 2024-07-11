<div>
  @forelse ($notifications as $notification)
    <div>
      <x-notification.notification-item wire:key='{{ $notification->id }}'  last="{{ $loop->last ? 1 : 0; }}" name="{{ $notification->data['message'] }}" time="{{ $notification->created_at->diffForHumans() }}" status="{{ $notification->data['type'] }}">
        <button type="button" wire:click="mark_as_read_faculty_notification('{{ $notification->id }}')">
          <x-status type="success" class="h-8 w-8 float-end p-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
          </x-status>
        </button>
      </x-notification.notification-item>
    </div>
  @empty
  <div class="w-full">
    <p class="mx-auto w-fit">No New Notifications.</p>
  </div>
  @endforelse
</div>
