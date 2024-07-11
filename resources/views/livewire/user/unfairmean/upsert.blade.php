<div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Unfairmean's
        <x-spinner />

    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="exam_patternclasses_id" :value="__('Select Exam Pattern Classes')" />
            <x-required />
            <x-input-select id="exam_patternclasses_id" wire:model.live="exam_patternclasses_id" name="exam_patternclasses_id" class="text-center w-full mt-1" :value="old('exam_patternclasses_id', $exam_patternclasses_id)" required autocomplete="exam_patternclasses_id">
                <x-select-option class="text-start" hidden> -- Select Exam Pattern Classes -- </x-select-option>
                @forelse ($exampatternclasses as $exampatternclass)
                <x-select-option wire:key="{{ $exampatternclass->id }}" value="{{ $exampatternclass->id }}" class="text-start">{{ $exampatternclass->patternclass->pattern->pattern_name }} {{ $exampatternclass->patternclass->courseclass->classyear->classyear_name??'-' }} {{ $exampatternclass->patternclass->courseclass->course->course_name }} </x-select-option>
                @empty
                <x-select-option class="text-start">Course Classes Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('exam_patternclasses_id')" class="mt-1" />
        </div>
        {{-- </div>

    <div class="grid grid-cols-1 md:grid-cols-2"> --}}
        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="exam_studentseatnos_id" :value="__('Seat No')" />
            <x-required />
            <x-input-select id="exam_studentseatnos_id" wire:model="exam_studentseatnos_id" name="exam_studentseatnos_id" class="text-center w-full mt-1" :value="old('exam_studentseatnos_id',$exam_studentseatnos_id)" required autofocus autocomplete="exam_studentseatnos_id">
                <x-select-option class="text-start" hidden> -- Select Seat NO -- </x-select-option>
                @foreach ($seatnos as $s_id =>$sname)
                <x-select-option wire:key="{{ $s_id }}" value="{{ $s_id }}" class="text-start">{{ $sname }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('exam_studentseatnos_id')" class="mt-2" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="unfairmeansmaster_id" :value="__('Place of Meeting')" />
            <x-required />
            <x-input-select id="unfairmeansmaster_id" wire:model="unfairmeansmaster_id" name="unfairmeansmaster_id" class="text-center w-full mt-1" :value="old('unfairmeansmaster_id',$unfairmeansmaster_id)" required autofocus autocomplete="unfairmeansmaster_id">
                <x-select-option class="text-start" hidden> -- Select Place -- </x-select-option>
                @foreach ($unfairmeans as $u_id =>$uname)
                <x-select-option wire:key="{{ $u_id }}" value="{{ $u_id }}" class="text-start">{{ $uname }}</x-select-option>
                @endforeach
            </x-input-select>
            <x-input-error :messages="$errors->get('unfairmeansmaster_id')" class="mt-2" />
        </div>
    </div>

    <x-table.table>
        <x-table.thead>
            <x-table.tr>
                <x-table.th name="id">No.</x-table.th>
                <x-table.th name="subject_name">Subject Name</x-table.th>
                <x-table.th>Action</x-table.th>
            </x-table.tr>
        </x-table.thead>
        <x-table.tbody>
            @forelse ($unfairmeanss as $index => $unfairmean)
            <x-table.tr wire:key="{{ $unfairmean->id }}">
                <x-table.td>{{ $index + 1 }}</x-table.td>
                <x-table.td>
                        @php
                        $subjectIds = explode(',', $unfairmean->subject_id);
                        $subjectNames = [];
                        foreach ($subjectIds as $subjectId) {
                            $subject = \App\Models\Subject::find($subjectId);
                            if ($subject) {
                                $subjectNames[] = $subject->subject_name;
                            }
                        }
                        $sub= implode(', ', $subjectNames);
                    @endphp
                    <x-table.text-scroll>
                        {{   $sub }}
                    </x-table.text-scroll>
                </x-table.td>
            </x-table.tr>
            @empty
            <x-table.tr>
                <x-table.td colspan="4" class="text-center">No Data Found</x-table.td>
            </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table.table>

    <x-form-btn wire:loading.attr="disable">
        Submit
    </x-form-btn>
</div>
