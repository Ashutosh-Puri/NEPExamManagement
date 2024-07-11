<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Helpline Document
  </div>
  <div class="grid grid-cols-1 md:grid-cols-3">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="student_helpline_query_id" :value="__('Query')" />
      <x-input-select id="student_helpline_query_id" wire:model="student_helpline_query_id" name="student_helpline_query_id" class="text-center w-full mt-1" :value="old('student_helpline_query_id',$student_helpline_query_id)" required  autocomplete="student_helpline_query_id">
          <x-select-option class="text-start" hidden> -- Select Query -- </x-select-option>
          @forelse ($student_helpline_queries as $query_id => $query_name)
              <x-select-option wire:key="{{ $query_id }}" value="{{ $query_id }}" class="text-start"> {{ $query_name }}</x-select-option>
          @empty
              <x-select-option value="" class="text-start">Queries Not Found</x-select-option>
          @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('student_helpline_query_id')" class="mt-2" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="document_name" :value="__('Document Name')" />
      <x-text-input id="document_name" type="text" wire:model="document_name" placeholder="{{ __('Enter Query Name') }}" name="document_name" class="w-full mt-1" :value="old('document_name', $document_name)" autocomplete="document_name" />
      <x-input-error :messages="$errors->get('document_name')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="is_active" :value="__('Status')" /> <br>
      <x-input-checkbox id="is_active"  wire:model="is_active"  name="is_active" :value="old('is_active',$is_active)" />
      <x-input-label for="is_active" class="inline mb-1 mx-2" :value="__('Make In Active')" />
      <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
    </div>
  </div>
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
