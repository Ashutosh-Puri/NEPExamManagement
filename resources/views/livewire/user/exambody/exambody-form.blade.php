<div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
    <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Exam Body
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="faculty_id" :value="__('Select Faculty')" />
            <x-required />
            <x-input-select id="faculty_id" wire:model="faculty_id" name="faculty_id" class="text-center w-full mt-1" :value="old('faculty_id', $faculty_id)" autocomplete="college_id">
                <x-select-option class="text-start" hidden> -- Select Faculty -- </x-select-option>
                @forelse ($faculties as $fid => $facultyname)
                <x-select-option wire:key="{{ $fid }}" value="{{$fid }}" class="text-start"> {{ $facultyname }} </x-select-option>
                @empty
                <x-select-option class="text-start">Faculty Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('faculty_id')" class="mt-1" />
        </div>

        <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="role_id" :value="__('Select Role')" />
            <x-required />
            <x-input-select id="role_id" wire:model="role_id" name="role_id" class="text-center w-full mt-1" :value="old('role_id', $role_id)" autocomplete="role_id">
                <x-select-option class="text-start" hidden> -- Select Role -- </x-select-option>
                @forelse ($roles as $roleid => $rolename)
                <x-select-option wire:key="{{ $roleid }}" value="{{$roleid }}" class="text-start"> {{ $rolename }} </x-select-option>
                @empty
                <x-select-option class="text-start">Roles Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('role_id')" class="mt-1" />
        </div>

        <div class="px-5 py-2  text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="college_id" :value="__('Select College')" />
            <x-required />
            <x-input-select id="college_id" wire:model="college_id" name="college_id" class="text-center w-full mt-1" :value="old('college_id', $college_id)" autocomplete="college_id">
                <x-select-option class="text-start" hidden> -- Select College -- </x-select-option>
                @forelse ($colleges as $collegeid => $collegename)
                <x-select-option wire:key="{{ $collegeid }}" value="{{$collegeid }}" class="text-start"> {{ $collegename }} </x-select-option>
                @empty
                <x-select-option class="text-start">Colleges Not Found</x-select-option>
                @endforelse
            </x-input-select>
            <x-input-error :messages="$errors->get('college_id')" class="mt-1" />
        </div>

        <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="is_active" :value="__('Status')" />
            <x-required />
            <x-input-select id="is_active" wire:model="is_active" name="is_active" class="text-center  w-full mt-1" :value="old('is_active',$is_active)" required autocomplete="is_active">
                <x-select-option class="text-start" hidden> -- Select -- </x-select-option>
                <x-select-option class="text-start" value="1">Active</x-select-option>
                <x-select-option class="text-start" value="0">Inactive</x-select-option>
            </x-input-select>
            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
        </div>

    </div>

    <section>
        <form wire:submit="photo_upload()">
            <div class="m-2 overflow-hidden bg-white border rounded  shadow dark:border-primary-darker dark:bg-darker ">
                <div class="px-2 py-2 font-semibold text-white dark:text-light bg-primary">Upload Photo & Sign
                    <x-spinner />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="m-5   col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                        <div class="flex items-center justify-between border-b p-4 dark:border-primary">
                            <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Upload Faculty Photo  <x-required /></h4>
                        </div>
                        <div class="relative h-auto p-4">
                            <div class=" text-sm text-gray-600 dark:text-gray-400 ">
                                <div class="flex flex-col items-center mx-auto space-x-6  ">
                                    <div class="shrink-0 p-2 ">
                                        @if ($profile_photo_path)
                                        <img style="width: 135px; height: 150px;" class="object-center object-fill  " src="{{ isset($profile_photo_path) ? $profile_photo_path->temporaryUrl() : 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/no-img.png'))) . '' }}" alt="Current profile photo" />
                                        @else
                                        @if (file_exists($profile_photo_path_old))
                                        <img style="width: 135px; height: 150px;" class="object-center object-fill rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($profile_photo_path_old))) }}" alt="Current profile photo" />
                                        @else
                                        <img style="width: 135px; height: 150px;" class="object-center object-fill rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/no-img.png'))) }}" alt="Current profile photo" />
                                        @endif
                                        @endif
                                    </div>
                                    <label class="block p-2">
                                        <span class="sr-only">Choose profile photo</span>
                                        <x-text-input id="profile_photo_path" wire:model.live="profile_photo_path" name="profile_photo_path" accept="image/png, image/jpeg , image/jpg" :value="old('profile_photo_path', $profile_photo_path)" autocomplete="profile_photo_path" type="file" class="block w-full text-sm dark:text-slate-500 text-black file:mr-4 file:py-2 file:px-4  border file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-darker" />
                                        <x-input-error :messages="$errors->get('profile_photo_path')" class="mt-2" />
                                    </label>
                                    <x-input-label wire:loading.remove wire:target="profile_photo_path" class="py-2" for="profile_photo_path" :value="__('Hint : 250KB , png , jpeg , jpg')" />
                                    <x-input-label wire:loading wire:target="profile_photo_path" class="py-2" for="profile_photo_path" :value="__('Uploading...')" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-5 col-span-1 rounded-md bg-white dark:bg-darker dark:border-primary-darker border">
                        <div class="flex items-center justify-between border-b p-4 dark:border-primary">
                            <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Upload Faculty Sign <x-required /></h4>
                        </div>
                        <div class="relative h-auto p-4">
                            <div class="p-5 text-sm text-gray-600 dark:text-gray-400 ">
                                <div class="flex flex-col items-center mx-auto space-x-6 ">
                                    <div class="shrink-0 py-10 ">
                                        @if ($sign_photo_path)
                                        <img style="width: 200px; height:82px;" class="object-center object-fill bg-white" src="{{ isset($sign_photo_path) ? $sign_photo_path->temporaryUrl() : 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/no-img.png'))) . '' }}" alt="Current profile photo" />
                                        @else
                                        @if (file_exists($sign_photo_path_old))
                                        <img style="width: 200px; height:82px;" class="object-center oobject-fill bg-white rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($sign_photo_path_old))) }}" alt="Current profile photo" />
                                        @else
                                        <img style="width: 200px; height:82px;" class="object-center oobject-fill bg-white rounded-md" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/no-img.png'))) }}" alt="Current profile photo" />
                                        @endif
                                        @endif
                                    </div>
                                    <label class="block p-2">
                                        <span class="sr-only">Choose profile photo</span>
                                        <x-text-input id="sign_photo_path" wire:model.live="sign_photo_path" name="sign_photo_path" accept="image/png, image/jpeg , image/jpg" :value="old('sign_photo_path', $sign_photo_path)" autocomplete="sign_photo_path" type="file" class="block w-full text-sm dark:text-slate-500 text-black file:mr-4 file:py-2 file:px-4  border file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-darker" />
                                        <x-input-error :messages="$errors->get('sign_photo_path')" class="mt-2" />
                                    </label>
                                    <x-input-label wire:loading.remove wire:target="sign_photo_path" class="py-2" for="sign_photo_path" :value="__('Hint : 50KB , png , jpeg , jpg')" />
                                    <x-input-label wire:loading wire:target="sign_photo_path" class="py-2" for="sign_photo_path" :value="__('Uploading...')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </section>
    
    <x-form-btn wire:target="sign_photo_path,sign_photo_path" wire:loading.attr="disable">
        Submit
    </x-form-btn>
</div>
