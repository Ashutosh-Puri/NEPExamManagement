<x-table.tr>
    <x-table.td>
        <x-table.text-scroll>{{ $audit_internal_tool->facultysubjecttool->subject->subject_name ?? '' }}</x-table.text-scroll>
    </x-table.td>
    <x-table.td>
        <x-table.text-scroll>{{ $audit_internal_tool->internaltooldocument->internaltoolmaster->toolname ?? '' }}</x-table.text-scroll>
    </x-table.td>
    <x-table.td>
        <x-table.text-scroll>{{ $audit_internal_tool->internaltooldocument->internaltooldocumentmaster->doc_name ?? '' }}</x-table.text-scroll>
    </x-table.td>
    <x-table.td>
        @if ($audit_internal_tool->document_fileName !== null && $audit_internal_tool->document_filePath !== null)
            <div wire:click="document_viewed">
                <x-view-image-model-btn title="{{ $audit_internal_tool->internaltooldocument->internaltooldocumentmaster->doc_name }}" i="1" src="{{ isset($audit_internal_tool->document_filePath) ? asset($audit_internal_tool->document_filePath) : asset('img/no-img.png') }}" />
            </div>
        @endif
    </x-table.td>
    <x-table.td>
        @if ($documentViewed)
            <x-form wire:submit="save_remark({{ $audit_internal_tool->id }})" id="{{ $audit_internal_tool->id }}">
                <div class="flex items-center mb-2">
                    <div class="px-1 py-1 mx-0 text-xs text-gray-600 dark:text-gray-400">
                        <x-input-select wire:model.live="verificationremark" class="text-center w-36 mt-1" autofocus required autocomplete="verificationremark">
                            <x-select-option class="text-start" hidden>-- Select --</x-select-option>
                            <x-select-option class="text-start" value="Completed">Completed</x-select-option>
                            <x-select-option class="text-start" value="Incomplete">Incomplete</x-select-option>
                        </x-input-select>
                    </div>
                    @if ($verificationremark === 'Incomplete')
                        <div class="flex items-center">
                            <x-text-input wire:model="other_verification_remark" type="text" name="other_verification_remark" placeholder="Remark" class="w-56 mt-1 mr-2" autofocus required autocomplete="other_verification_remark" />
                        </div>
                    @endif
                    <x-table.upload-btn i="0" class="cursor-pointer px-2 py-2">Save</x-table.upload-btn>
                </div>
                <x-input-error :messages="$errors->get('verificationremark')" class="mt-2" />
                @if ($verificationremark === 'Incomplete')
                    <x-input-error :messages="$errors->get('other_verification_remark')" class="mt-2" />
                @endif
            </x-form>
        @endif
    </x-table.td>
</x-table.tr>
