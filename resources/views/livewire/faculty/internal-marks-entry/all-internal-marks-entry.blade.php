<div>
    @if ($mode == 'edit')
        <div>
            <x-card-header heading="Add/Edit Marks">
                <x-back-btn wire:click="setmode('all')" />
            </x-card-header>
            @include('livewire.faculty.internal-marks-entry.internal-marks-form')
        </div>
    @elseif($mode == 'all')
        <x-breadcrumb.breadcrumb>
            <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
            <x-breadcrumb.link name="Marks Entry" />
        </x-breadcrumb.breadcrumb>
        <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
            <div class="bg-primary px-2 py-3 font-semibold text-white dark:text-light">
                Internal Marks Entry
            </div>
            <div class="grid grid-cols-1 md:grid-cols-1">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="appointed_role_id" :value="__('Appointed Role')" /><x-required />
                    <x-input-select id="appointed_role_id" wire:model.live="appointed_role_id" name="appointed_role_id" class="text-center w-full mt-1" :value="old('appointed_role_id', $appointed_role_id)" required autocomplete="appointed_role_id">
                        <x-select-option hidden>--- Select Appointed Role ---</x-select-option>
                        @forelse ($appointed_roles as $appointed_role)
                            <x-select-option wire:key="{{ $appointed_role['id'] }}" value="{{ $appointed_role['value'] }}" class="text-start">{{ $appointed_role['display'] }}</x-select-option>
                        @empty
                            <x-select-option class="text-start">Appointed Role Not Found</x-select-option>
                        @endforelse
                    </x-input-select>
                    <x-input-error :messages="$errors->get('appointed_role_id')" class="mt-1" />
                </div>
            </div>
        </div>
        @if (isset($appointed_role_id))
            <div class="m-2 overflow-x-scroll rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
                <div>
                    <x-table.frame x="0" s="0" r="0" p="0" sp="0">
                        <x-slot:body>
                            <x-table.table>
                                <x-table.thead>
                                    <x-table.tr>
                                        <x-table.th>Class</x-table.th>
                                        <x-table.th>Subject</x-table.th>
                                        <x-table.th>Batch No.</x-table.th>
                                        <x-table.th>Download Excel Format</x-table.th>
                                        <x-table.th>Add/Edit</x-table.th>
                                        <x-table.th>Preview</x-table.th>
                                        <x-table.th>Confirm</x-table.th>
                                        <x-table.th>Print</x-table.th>
                                    </x-table.tr>
                                </x-table.thead>
                                <x-table.tbody>
                                    @forelse ($appointed_batches as $appointed_batch)
                                        <x-table.tr>
                                            <x-table.td>
                                                <x-table.text-scroll>
                                                    {{ isset($appointed_batch->exam_patternclass->patternclass->pattern->pattern_name) ? $appointed_batch->exam_patternclass->patternclass->pattern->pattern_name : '-' }}
                                                    {{ isset($appointed_batch->exam_patternclass->patternclass->courseclass->classyear->classyear_name) ? $appointed_batch->exam_patternclass->patternclass->courseclass->classyear->classyear_name : '-' }}
                                                    {{ isset($appointed_batch->exam_patternclass->patternclass->courseclass->course->course_name) ? $appointed_batch->exam_patternclass->patternclass->courseclass->course->course_name : '-' }}
                                                </x-table.text-scroll>
                                            </x-table.td>
                                            <x-table.td>
                                                <x-table.text-scroll>
                                                    {{ isset($appointed_batch->subject->subject_code) ? $appointed_batch->subject->subject_code : '-' }}
                                                    {{ isset($appointed_batch->subject->subject_name) ? $appointed_batch->subject->subject_name : '-' }}
                                                </x-table.text-scroll>
                                            </x-table.td>
                                            <x-table.td>
                                                <x-table.text-scroll>
                                                    {{ $appointed_batch->created_at->format('Y') . $appointed_batch->subject_id . str_pad($appointed_batch->id, 5, '0', STR_PAD_LEFT) }}
                                                </x-table.text-scroll>
                                            </x-table.td>
                                            <x-table.td>
                                                <x-table.download wire:click='download_format({{ $appointed_batch->id }})' />
                                            </x-table.td>
                                            <x-table.td>
                                                @if ($appointed_batch->status == '5')
                                                    <x-table.edit />
                                                @else
                                                    <x-table.edit wire:click='edit({{ $appointed_batch->id }})' />
                                                @endif
                                            </x-table.td>
                                            <x-table.td>
                                                @if (($appointed_batch->status == 2 || $appointed_batch->status == 3) && $appointed_batch->totalBatchsize == $appointed_batch->totalAbsent + $appointed_batch->totalMarksentry)
                                                    <form method="post" id="preview_marks" action="{{ route('faculty.preview_marks') }}" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="batch_marks_id" value="{{ $appointed_batch->id }}">
                                                        <x-table.preview type="submit" />
                                                    </form>
                                                @else
                                                    <x-table.preview />
                                                @endif
                                            </x-table.td>
                                            <x-table.td>
                                                @if ($appointed_batch->status == 2 || $appointed_batch->status == 3)
                                                    <x-table.approve wire:click="confirm_marks({{ $appointed_batch->id }})" />
                                                @else
                                                    <x-table.reject />
                                                @endif
                                            </x-table.td>
                                            <x-table.td>
                                                @if ($appointed_batch->status == 5)
                                                    <form method="post" id="print_marks" action="{{ route('faculty.print_marks') }}" style="display: inline;">
                                                        @csrf
                                                        <input type="hidden" name="print_marks_id" value="{{ $appointed_batch->id }}">
                                                        <x-table.print type="submit" />
                                                    </form>
                                                @else
                                                    <x-table.print />
                                                @endif
                                            </x-table.td>
                                        </x-table.tr>
                                    @empty
                                        <x-table.tr>
                                            <x-table.td colspan='9' class="text-center">No Data Found</x-table.td>
                                        </x-table.tr>
                                    @endforelse
                                </x-table.tbody>
                            </x-table.table>
                        </x-slot>
                        {{-- <x-slot:footer>
                            <x-table.paginate :data="$appointed_batches" />
                        </x-slot> --}}
                    </x-table.frame>
                </div>
            </div>
        @endif
    @endif
</div>
