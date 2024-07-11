<div>
  <div>
    @if ($is_bank)
      <div class="flex items-center mb-2 gap-x-5 ">
        <form id="preview" name="preview" class="inline" action="{{ route('faculty.preview_question_paper') }}" method="post" target="_blank">
          @csrf
          <input type="hidden" name="questionpaperbank" id="questionpaperbank" value="{{ $is_bank->id }}">
          <x-table.view type="submit">View</x-table.view>
        </form>
        @if (isset($is_bank->papersubmission->is_confirmed) && $is_bank->papersubmission->is_confirmed == 0)
          <x-table.delete wire:click='delete_question_paper_set_document({{ $is_bank->papersubmission_id }},{{ $is_bank->id }})'>Delete </x-table.delete>
        @endif
      </div>
    @else
      <form wire:submit='upload_question_paper_set_document()'>
        <div class="flex items-center mb-2">
          <span class="inline-flex">
            <x-input-file class="text-xs file:px-2 col-span-8 file:rounded-sm file:text-xs min-w-75 mr-2" wire:click='reset_input()' name="questionbank.{{ $set->id }}" id="questionbank.{{ $set->id }}" wire:model="questionbank.{{ $set->id }}" accept=".pdf" />
            <span class="colspan-4 w-25">
              @if (isset($questionbank[$set->id]))
                <x-table.upload-btn i="0" wire:loading.remove wire:target="questionbank.{{ $set->id }}" class="cursor-pointer px-5">Upload</x-table.upload-btn>
              @else
                <x-table.upload-btn type="button" i="0" wire:loading.remove wire:target="questionbank.{{ $set->id }}" class=" cursor-not-allowed bg-red-400 px-5">No File</x-table.upload-btn>
                <x-table.upload-btn type="button" i="0" wire:loading wire:target="questionbank.{{ $set->id }}" class="px-4 !bg-blue-400 cursor-not-allowed">Loading</x-table.upload-btn>
              @endif
            </span>
          </span>
        </div>
        @error("questionbank.{$set->id}")
          <div class="text-sm text-red-600 dark:text-red-400  space-y-1">{{ $message }}</div>
        @enderror
      </form>
    @endif
  </div>
</div>
