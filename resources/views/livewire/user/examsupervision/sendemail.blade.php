<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Send Orders
    </div>

    <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
        <x-input-label for="course_id" :value="__('Exam Session')" />
        <x-required />
        <x-input-select id="course_id" wire:model.live="session_date" name="course_id" class="text-center w-full mt-1" required autocomplete="course_id">
            <x-select-option class="text-start" hidden>-- Select Exam Session --</x-select-option>
            @foreach($allsession as $type)
            <x-select-option value="{{$type->id}}" class="text-start">
                {{date('d-M-Y', strtotime($type->from_date))}} To {{date('d-M-Y', strtotime($type->to_date))}}
            </x-select-option>
            @endforeach
        </x-input-select>
        <x-input-error :messages="$errors->get('course_id')" class="mt-1" />
    </div>

    <x-form-btn  wire:loading.attr="disable" wire:click="createsendmail">
        Send Email
    </x-form-btn>

</div>





