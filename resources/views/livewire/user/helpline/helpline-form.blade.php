<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
  <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
    Modification Request
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2">
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="student_id" :value="__('Select Student')" />
      <x-input-select id="student_id" wire:model="student_id" name="student_id" class="text-center w-full mt-1" :value="old('student_id', $student_id)" required autocomplete="student_id">
        <x-select-option class="text-start" hidden> -- Select Student -- </x-select-option>
        @forelse ($students as $studentid => $student_name)
          <x-select-option wire:key="{{  $studentid }}" value="{{  $studentid }}" class="text-start"> {{ $student_name }} </x-select-option>
        @empty
          <x-select-option class="text-start">Students Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('student_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="student_helpline_query_id" :value="__('Select What You Want To Change')" />
      <x-input-select id="student_helpline_query_id" wire:model.live="student_helpline_query_id" name="student_helpline_query_id" class="text-center w-full mt-1" :value="old('student_helpline_query_id', $student_helpline_query_id)" required autocomplete="student_helpline_query_id">
        <x-select-option class="text-start" hidden> -- Select Your Query -- </x-select-option>
        @forelse ($helplinequeries as $helplinequery_id => $helplinequery_name)
          <x-select-option wire:key="{{ $helplinequery_id }}" value="{{ $helplinequery_id }}" class="text-start"> {{ $helplinequery_name }} </x-select-option>
        @empty
          <x-select-option class="text-start">Queries Not Found</x-select-option>
        @endforelse
      </x-input-select>
      <x-input-error :messages="$errors->get('student_helpline_query_id')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="old_query" :value="__('Current ' . $current_query)" />
      <x-text-input id="old_query" type="text" wire:model="old_query" placeholder="{{ __('Enter Current ' . $current_query) }}" name="old_query" class="w-full mt-1" :value="old('old_query', $old_query)" autocomplete="old_query" />
      <x-input-error :messages="$errors->get('old_query')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="new_query" :value="__('New ' . $current_query)" />
      <x-text-input id="new_query" type="text" wire:model="new_query" placeholder="{{ __('Enter New ' . $current_query) }}" name="new_query" class="w-full mt-1" :value="old('new_query', $new_query)" autocomplete="new_query" />
      <x-input-error :messages="$errors->get('new_query')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="description" :value="__('Description')" />
      <x-textarea id="description" placeholder="Enter Description" type="text" wire:model="description" name="description" class="w-full mt-1" :value="old('description', $description)" autocomplete="description" />
      <x-input-error :messages="$errors->get('description')" class="mt-1" />
    </div>
    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
      <x-input-label for="remark" :value="__('Remark')" />
      <x-textarea id="remark" placeholder="Enter Remark" type="text" wire:model="remark" name="remark" class="w-full mt-1" :value="old('remark', $remark)" autocomplete="remark" />
      <x-input-error :messages="$errors->get('remark')" class="mt-1" />
    </div>
  </div>
  @if (count($documents) > 0)
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Upload Documents <x-spinner />
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mx-auto">
        @foreach ($documents as $doc)
          <div class="flex flex-col items-center space-x-1 border rounded-md border-primary m-2 ">
            <div class="shrink-0 p-2">
              @if (isset($uploaded_documents[$doc->id]))
                <img style="width: 135px; height: 150px;" class="object-center object-fill  " src="{{ isset($uploaded_documents[$doc->id]) ? $uploaded_documents[$doc->id]->temporaryUrl() : asset('img/no-img.png') }}" alt="{{ $doc->document_name }}" />
              @else
                <img style="width: 135px; height: 150px;" class="object-center object-fill  " src="{{ isset($uploaded_documents_old[$doc->id]) ? asset($uploaded_documents_old[$doc->id]) : asset('img/no-img.png') }}" alt="{{ $doc->document_name }}" />
              @endif
            </div>
            <label class="block p-2">
              <x-input-label for="{{ $doc->id }}" :value="__('Upload ' . $doc->document_name)" />
              <x-input-file name="uploaded_documents.{{ $doc->id }}" id="{{ $doc->id }}" wire:model.live="uploaded_documents.{{ $doc->id }}" accept="image/png, image/jpeg , image/jpg" />
              @error("uploaded_documents.{$doc->id}")
                <div class="text-sm text-red-600 dark:text-red-400 space-y-1">{{ $message }}</div>
              @enderror
            </label>
            <x-input-label wire:loading.remove wire:target="uploaded_documents.{{ $doc->id }}" class="py-2" for="{{ $doc->id }}" :value="__('Hint : 300KB , png , jpeg , jpg')" />
            <x-input-label wire:loading wire:target="uploaded_documents.{{ $doc->id }}" class="py-2" for="{{ $doc->id }}" :value="__('Uploading...')" />
          </div>
        @endforeach
      </div>
    </div>
  @endif
  <x-form-btn wire:loading.attr="disabled">Submit</x-form-btn>
</div>
