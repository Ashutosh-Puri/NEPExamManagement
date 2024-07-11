<div>
    <x-breadcrumb.breadcrumb>
        <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
        <x-breadcrumb.link name="Upload Document's" />
    </x-breadcrumb.breadcrumb>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary  px-2 py-3 font-semibold text-white dark:text-light">
            Fetch Tool Documents
            <a class="float-end" wire:click="setmode('1')" wire:navigate href="{{ route('faculty.all_assign_tools', 'add') }}"><x-status class="py-1 border-white border mx-1" type="success"> ADD Tool </x-status></a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="academicyear_id" :value="__('Academic Year')" />
                <x-input-select id="academicyear_id" wire:model.live="academicyear_id" name="academicyear_id" class="text-center @error('academicyear_id') is-invalid @enderror w-full mt-1" :value="old('academicyear_id', $academicyear_id)" required autofocus autocomplete="academicyear_id">
                    <x-select-option class="text-start" hidden> -- Select Academic Year -- </x-select-option>
                    @forelse ($academicyears as $academicyearid => $academicyearname)
                        <x-select-option wire:key="{{ $academicyearid }}" value="{{ $academicyearid }}" class="text-start">{{ $academicyearname }}</x-select-option>
                    @empty
                        <x-select-option class="text-start">Academic Years Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('academicyear_id')" class="mt-2" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="patternclass_id" :value="__('Select Class')" />
                <x-input-select id="patternclass_id" wire:model.live="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
                    <x-select-option class="text-start" hidden> -- Select Class -- </x-select-option>
                    @forelse ($pattern_classes as $pattern_class)
                        <x-select-option wire:key="{{ $pattern_class->id }}" value="{{ $pattern_class->id }}" class="text-start"> {{ $pattern_class->classyear_name ?? '-' }} {{ $pattern_class->course_name ?? '-' }} {{ $pattern_class->pattern_name ?? '-' }} </x-select-option>
                    @empty
                        <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="semester_id" :value="__('Semester')" />
                <x-input-select id="semester_id" wire:model.live="semester_id" name="semester_id" class="text-center @error('semester_id') is-invalid @enderror w-full mt-1" :value="old('semester_id', $semester_id)" required autofocus autocomplete="semester_id">
                    <x-select-option class="text-start" hidden> -- Select Semester -- </x-select-option>
                    @forelse ($semesters as $semesterid => $semestername)
                        <x-select-option wire:key="{{ $semesterid }}" value="{{ $semestername }}" class="text-start"> {{ $semestername }} </x-select-option>
                    @empty
                        <x-select-option class="text-start">Semesters Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('semester_id')" class="mt-2" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="subject_id" :value="__('Subject')" />
                <x-input-select id="subject_id" wire:model.live="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" required autocomplete="subject_id">
                    <x-select-option hidden> -- Select Subject -- </x-select-option>
                    @forelse ($subjects as $subjectid => $subject)
                        <x-select-option wire:key="{{ $subjectid }}" value="{{ $subject->id }}" class="text-start">
                            {{ $subject['subject_code'] }} - {{ $subject['subject_name'] }}
                        </x-select-option>
                    @empty
                        <x-select-option class="text-start">Subjects Not Found</x-select-option>
                    @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
            </div>
        </div>
    </div>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary px-2 text-center py-1 font-semibold text-white dark:text-light">
            Documents Pending Upload
        </div>
        <div class="overflow-x-auto">
            <x-table.table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>Tool Name</x-table.th>
                        <x-table.th>Document Name</x-table.th>
                        <x-table.th>Preview</x-table.th>
                        <x-table.th>Action</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse ($facultyinternaldocuments as $facultyinternaldocument)
                        <livewire:faculty.internal-audit.upload-document-row.upload-document-row :key="$facultyinternaldocument->id" :$facultyinternaldocument />
                    @empty
                        <x-table.tr>
                            <x-table.td colspan='4' class="text-center">No Data Found</x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table.table>
        </div>
    </div>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
        <div class="bg-primary px-2 text-center py-1 font-semibold text-white dark:text-light">
            Uploaded Documents
        </div>
        <div class="overflow-x-scroll">
            <x-table.table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th>Tool Name</x-table.th>
                        <x-table.th>Document Name</x-table.th>
                        <x-table.th>Action</x-table.th>
                    </x-table.tr>
                </x-table.thead>
                <x-table.tbody>
                    @forelse ($uploaded_documents as $uploaded_document)
                        <x-table.tr wire:key="{{ $uploaded_document->id }}">
                            <x-table.td>
                                <x-table.text-scroll>{{ $uploaded_document->internaltooldocument->internaltoolmaster->toolname ?? '' }}</x-table.text-scroll>
                            </x-table.td>
                            <x-table.td>
                                <x-table.text-scroll>{{ $uploaded_document->internaltooldocument->internaltooldocumentmaster->doc_name ?? '' }}</x-table.text-scroll>
                            </x-table.td>
                            <x-table.td>
                                <div class="flex items-center space-x-2">
                                    <x-view-image-model-btn title="{{ $uploaded_document->internaltooldocument->internaltooldocumentmaster->doc_name }}" i="1" src="{{ isset($uploaded_document->document_filePath) ? asset($uploaded_document->document_filePath) : asset('img/no-img.png') }}" />
                                    @if (!$uploaded_document->facultysubjecttool->freeze_by_faculty)
                                        <x-table.delete wire:click="deleteconfirmation({{ $uploaded_document->id }})" />
                                    @endif
                                </div>
                            </x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan='3' class="text-center">No Data Found</x-table.td>
                        </x-table.tr>
                    @endforelse
                    @if (isset($subject_id))
                        @if ($uploaded_documents->isNotEmpty() && $this->show_freeze_button(optional($uploaded_documents->first())->facultysubjecttool_id ?? ''))
                            <x-table.tr>
                                <x-table.td colspan='3' class="text-right">
                                    <x-table.approve wire:click='freeze_tool({{ $uploaded_documents }})' i="0">Save</x-table.approve>
                                </x-table.td>
                            </x-table.tr>
                        @endif
                    @endif
                </x-table.tbody>
            </x-table.table>
        </div>
    </div>
</div>
