<div>
  <x-breadcrumb.breadcrumb>
    <x-breadcrumb.link route="faculty.dashboard" name="Dashboard" />
    <x-breadcrumb.link name="Audit Internal Tool's" />
  </x-breadcrumb.breadcrumb>
  <x-card-header heading="Audit Internal Tool's" />

  <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
      Internal Tool Verification
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
            <x-select-option wire:key="{{ $pattern_class->id }}" value="{{ $pattern_class->id }}" class="text-start"> {{ $pattern_class->classyear_name ?? '-' }} {{ $pattern_class->course_name ?? '-' }} {{ $pattern_class->pattern_name ?? '-' }}</x-select-option>
          @empty
            <x-select-option class="text-start">Pattern Classes Not Found</x-select-option>
          @endforelse
        </x-input-select>
        <x-input-error :messages="$errors->get('patternclass_id')" class="mt-1" />
      </div>
    </div>
  </div>
  <div class="m-2 overflow-x-scroll rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <x-table.table>
      <x-table.thead>
        <x-table.tr>
          <x-table.th>Subject Name</x-table.th>
          <x-table.th>Tool Name</x-table.th>
          <x-table.th>Document Name</x-table.th>
          <x-table.th>View</x-table.th>
          <x-table.th>Remark</x-table.th>
        </x-table.tr>
      </x-table.thead>
      <x-table.tbody>
        @forelse ($audit_internal_tools as $audit_internal_tool)
          <livewire:faculty.internal-audit.verification-remark.verification-remark :key="$audit_internal_tool->id" :$audit_internal_tool />
        @empty
          <x-table.tr>
            <x-table.td colspan='5' class="text-center">No Data Found</x-table.td>
          </x-table.tr>
        @endforelse
      </x-table.tbody>
    </x-table.table>
  </div>
</div>
