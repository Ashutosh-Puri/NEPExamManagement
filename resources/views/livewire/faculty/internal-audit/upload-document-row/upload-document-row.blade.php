<x-table.tr>
    <x-table.td>
        <x-table.text-scroll>{{ $faculty_internal_document_data->internaltooldocument->internaltoolmaster->toolname ?? '' }}</x-table.text-scroll>
    </x-table.td>
    <x-table.td>
        <x-table.text-scroll>{{ $faculty_internal_document_data->internaltooldocument->internaltooldocumentmaster->doc_name ?? '' }}</x-table.text-scroll>
    </x-table.td>
    <x-table.td>
        <div class="h-[80px] w-[80px] rounded overflow-hidden">
            @if ($document_to_upload)
                @if ($document_to_upload->getClientOriginalExtension() === 'pdf')
                    <x-pdf-image />
                @else
                    <img src="{{ asset($document_to_upload->temporaryUrl()) }}" alt="File Preview" class="h-full w-full object-cover" />
                @endif
            @else
                <x-no-preview />
            @endif
        </div>
    </x-table.td>
    <x-table.td>
        <x-form wire:submit="save({{ $faculty_internal_document_data->id }})" id="{{ $faculty_internal_document_data->id }}">
            <div class="flex items-center w-full mb-2">
                <x-input-file wire:click="resetinput" class="text-xs file:px-2 file:rounded-sm file:text-xs min-w-[70%] mr-2" name="document_to_upload" id="document_to_upload" wire:model="document_to_upload" accept="image/png, image/jpeg, image/jpg, .pdf" />
                @if (isset($document_to_upload))
                    <x-table.upload-btn i="0" wire:loading.remove wire:target="document_to_upload" class="cursor-pointer px-5" >Upload</x-table.upload-btn>
                @else
                    <x-table.upload-btn type="button" i="0" wire:loading.remove wire:target="document_to_upload" class=" cursor-not-allowed bg-red-400 px-5">No File</x-table.upload-btn>
                    <x-table.upload-btn type="button" i="0" wire:loading wire:target="document_to_upload" class="px-4 !bg-blue-400 cursor-not-allowed">Loading</x-table.upload-btn>
                @endif
            </div>
            <x-input-error :messages="$errors->get('document_to_upload')" class="mt-2" />
        </x-form>
    </x-table.td>
</x-table.tr>
