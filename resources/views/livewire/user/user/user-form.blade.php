<div>
    <section>
        <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
            <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
                User
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="name" :value="__(' Name')" />
                    <x-required />
                    <x-text-input id="name" type="text" wire:model="name" name="name" class="w-full mt-1" :value="old('name',$name)" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="email" :value="__(' Email')" />
                    <x-required />
                    <x-text-input id="email" type="email" wire:model="email" name="email" class="w-full mt-1" :value="old('email',$email)" required autofocus autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2">

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="password" :value="__(' Password')" />
                    <x-required />
                    <x-text-input id="password" type="password" wire:model="password" name="password" class="w-full mt-1" :value="old('password',$password)" required autofocus autocomplete="password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="user_contact_no" :value="__(' Contact No')" />
                    <x-required />
                    <x-text-input id="user_contact_no" type="number" wire:model="user_contact_no" name="user_contact_no" class="w-full mt-1" :value="old('user_contact_no',$user_contact_no)" required autofocus autocomplete="user_contact_no" />
                    <x-input-error :messages="$errors->get('user_contact_no')" class="mt-2" />
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2">

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="college_id" :value="__('College')" />
                    <x-required />
                    <x-input-select id="college_id" wire:model="college_id" name="college_id" class="text-center w-full mt-1" :value="old('college_id',$college_id)" required autofocus autocomplete="college_id">
                        <x-select-option class="text-start" hidden> -- Select College -- </x-select-option>
                        @foreach ($colleges as $c_id=>$cname)
                        <x-select-option wire:key="{{ $c_id}}" value="{{ $c_id }}" class="text-start">{{$cname }}</x-select-option>
                        @endforeach
                    </x-input-select>
                    <x-input-error :messages="$errors->get('college_id')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="department_id" :value="__('Department')" />
                    <x-required />
                    <x-input-select id="department_id" wire:model="department_id" name="department_id" class="text-center w-full mt-1" :value="old('department_id',$department_id)" required autofocus autocomplete="department_id">
                        <x-select-option class="text-start" hidden> -- Select Department -- </x-select-option>
                        @foreach ($departments as $d_id=>$dname)
                        <x-select-option wire:key="{{ $d_id }}" value="{{ $d_id}}" class="text-start">{{$dname }}</x-select-option>
                        @endforeach
                    </x-input-select>
                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2">

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="role_id" :value="__('Role')" />
                    <x-required />
                    <x-input-select id="role_id" wire:model="role_id" name="role_id" class="text-center w-full mt-1" :value="old('role_id',$role_id)" required autofocus autocomplete="role_id">
                        <x-select-option class="text-start" hidden> -- Select Role -- </x-select-option>
                        @foreach ($roles as $r_id=>$rname)
                        <x-select-option wire:key="{{ $r_id }}" value="{{ $r_id }}" class="text-start">{{$rname }}</x-select-option>
                        @endforeach
                    </x-input-select>
                    <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                </div>

                <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-input-label for="is_active" :value="__('Is Active')" />
                    <x-required />
                    <x-input-select id="is_active" wire:model="is_active" name="is_active" class="text-center  w-full mt-1" :value="old('is_active',$is_active)" required autocomplete="is_active">
                        <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                        <x-select-option class="text-start" value="1">Yes</x-select-option>
                        <x-select-option class="text-start" value="0">No</x-select-option>
                    </x-input-select>
                    <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                </div>


            </div>

            <div class="h-20 p-2">
                <x-form-btn>
                    Submit
                </x-form-btn>
            </div>
        </div>
    </section>
</div>
