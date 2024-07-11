<div>
  <x-card-header heading="Update Faculty Role">
    <x-back-btn wire:click="setmode('all')" />
  </x-card-header>
  <x-form wire:submit="update({{ $faculty_id }})">
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        {{ $faculty_name }} Update Roles
      </div>
      <div class="grid grid-cols-1 md:grid-cols-4">
        @foreach ($roles as $role)
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-checkbox id="faculty_roles.{{ $role->id }}" wire:model="faculty_roles.{{ $role->id }}" name="faculty_roles.{{ $role->id }}" class=" mt-1" />
            <x-input-label for="faculty_roles.{{ $role->id }}" class="mx-4" value="{{ $role->role_name }}" />
          </div>
        @endforeach
      </div>
      <x-form-btn>Submit</x-form-btn>
    </div>
  </x-form>
</div>
