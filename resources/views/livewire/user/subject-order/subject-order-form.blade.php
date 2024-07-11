<div>
  <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Create Subject Order
      <x-spinner />
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 py-2">
      <div class="px-2 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="patternclass_id" :value="__('Select Patternclass')" />
        <x-required />
        <x-input-select id="patternclass_id" wire:model.live="patternclass_id" name="patternclass_id" class="text-center w-full mt-1" :value="old('patternclass_id', $patternclass_id)" required autocomplete="patternclass_id">
          <x-select-option class="text-start" hidden> -- Select Pattern Class -- </x-select-option>
          @forelse ($patternclasses as $pattern_calss)
            <x-select-option wire:key="{{ $pattern_calss->id }}" value="{{ $pattern_calss->id }}" class="text-start"> {{ $pattern_calss->classyear_name ?? '-' }} {{ $pattern_calss->course_name ?? '-' }} {{ $pattern_calss->pattern_name ?? '-' }} </x-select-option>
          @empty
            <x-select-option class="text-start"> Pattern Classes Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
      </div>
      <div class="px-2 py-2  text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="subject_id" :value="__('Select Subject')" />
        <x-required />
        <x-input-select id="subject_id" wire:model="subject_id" name="subject_id" class="text-center w-full mt-1" :value="old('subject_id', $subject_id)" required autocomplete="subject_id">
          <x-select-option class="text-start" hidden> -- Select Subject -- </x-select-option>
          @forelse ($subjects as $subjectid => $subjectname)
            <x-select-option wire:key="{{ $subjectid }}" value="{{ $subjectid }}" class="text-start"> {{ $subjectname }} </x-select-option>
          @empty
            <x-select-option class="text-start">Subjects Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('subject_id')" class="mt-1" />
      </div>
      <div class="px-2 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="department_id" :value="__('Select Department')" />
        <x-required />
        <x-input-select id="department_id" wire:model.live="department_id" name="department_id" class="text-center w-full mt-1" :value="old('department_id', $department_id)" required autocomplete="patternclass_id">
          <x-select-option class="text-start" hidden> -- Select Department -- </x-select-option>
          @forelse ($departments as $dept_id => $department_name)
            <x-select-option wire:key="{{ $dept_id }}" value="{{ $dept_id }}" class="text-start"> {{ $department_name ?? '-' }} </x-select-option>
          @empty
            <x-select-option class="text-start">Department Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('department_id')" class="mt-1" />
      </div>
    </div>

    <x-table.table>
      <x-table.thead>
        <x-table.tr>
          <x-table.th>No.</x-table.th>
          <x-table.th>Post</x-table.th>
          <x-table.th>Faculty</x-table.th>
        </x-table.tr>
      </x-table.thead>
      <x-table.tbody>
        @foreach ($examorderposts as $post)
          <x-table.tr wire:key="{{ $post->id }}">
            <x-table.td> {{ $post->id }}</x-table.td>
            <x-table.td>
              {{ $post->post_name }}
            </x-table.td>
            <x-table.td>
              <div class="px-2 py-2  text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="faculty_ids.{{ $post->id }}" />
                <x-input-select id="faculty_ids.{{ $post->id }}" wire:model="faculty_ids.{{ $post->id }}" name="faculty_ids.{{ $post->id }}" class="text-center w-full mt-1">
                  <x-select-option class="text-start" hidden> -- Select Faculty -- </x-select-option>
                  @forelse ($faculties as $f_id => $fname)
                    <x-select-option wire:key="{{ $f_id }}" value="{{ $f_id }}" class="text-start"> {{ $fname }} </x-select-option>
                  @empty
                    <x-select-option class="text-start">Faculty Not Found</x-select-option>
                  @endforelse
                </x-input-select>
                <x-input-error :messages="$errors->get('faculty_ids.{ $post->id }')" class="mt-1" />
              </div>
            </x-table.td>
          </x-table.tr>
        @endforeach
      </x-table.tbody>
    </x-table.table>
    <x-form-btn wire:loading.attr="disabled">
      Submit
    </x-form-btn>
  </div>
