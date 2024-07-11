<div>
  <x-card-header heading="View Profile">
    <a href="{{ route('student.dashboard') }}"><x-back-btn /></a>
  </x-card-header>
  <section class="p-2">
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Student Information <x-spinner />
      </div>
      <div>
        <div class="grid grid-cols-1 md:grid-cols-3">
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="student_name" :value="__('Name')" />
            <x-input-show id='student_name' :value="$student_name" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="email" :value="__('Email')" />
            <x-input-show id='email' :value="$email" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="mobile_no" :value="__('Mobile')" />
            <x-input-show id='mobile_no' :value="$mobile_no" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="date_of_birth" :value="__('Date Of Birth')" />
            <x-input-show id='date_of_birth' :value="$date_of_birth" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="memid" :value="__('Member ID')" />
            <x-input-show id='memid' :value="$memid" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="abcid" :value="__('ABC ID')" />
            <x-input-show id='abcid' :value="$abcid" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="adharnumber" :value="__('Aadhar Number')" />
            <x-input-show id='adharnumber' :value="$adharnumber" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="aadhar_name" :value="__('Aadhar Name')" />
            <x-input-show id='aadhar_name' :value="$aadhar_name" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="father_name" :value="__('Father Name')" />
            <x-input-show id='father_name' :value="$father_name" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="mother_name" :value="__('Mother Name')" />
            <x-input-show id='mother_name' :value="$mother_name" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="mother_name" :value="__('Mother Name')" />
            <x-input-show id='mother_name' :value="$mother_name" />
          </div>
          @php
            $caste_name = $category_name = '';

            $caste = App\Models\Caste::find($caste_id);
            $caste_category = App\Models\Castecategory::find($caste_category_id);

            if (isset($caste->caste_name)) {
                $caste_name = $caste->caste_name;
            }
            if (isset($caste_category->caste_category)) {
                $category_name = $caste_category->caste_category;
            }
          @endphp
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="caste" :value="__('Cast')" />
            <x-input-show id='caste' :value="$caste_name" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="caste_category" :value="__('Cast Category')" />
            <x-input-show id='caste_category' :value="$category_name" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            @php
              $g = $gender == 'M' ? 'Male' : ($gender == 'F' ? 'Female' : 'Transgender');
            @endphp
            <x-input-label for="gender" :value="__('Gender')" />
            <x-input-show id='gender' :value="$g" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="nationality" :value="__('Nationality')" />
            <x-input-show id='nationality' :value="$nationality" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            @php
              $n = $is_noncreamylayer == 1 ? 'Yes' : 'No';
            @endphp
            <x-input-label for="is_noncreamylayer" :value="__('Is Non Creamy Layer')" />
            <x-input-show id='is_noncreamylayer' :value="$n" />
          </div>
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            @php
              $h = $is_handicap == 1 ? 'Yes' : 'No';
            @endphp
            <x-input-label for="is_handicap" :value="__('Is Handicapped')" />
            <x-input-show id='is_handicap' :value="$h" />
          </div>
        </div>
      </div>
    </div>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">Uploaded Photo & Sign </div>
      <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="col-span-1 m-5 rounded-md border bg-white dark:border-primary-darker dark:bg-darker">
          <div class="flex items-center justify-between border-b p-2 dark:border-primary">
            <h6 class="text-md font-semibold text-gray-500 dark:text-light">Uploaded Student Photo</h6>
          </div>
          <div class="relative h-auto p-4">
            <div class="text-sm text-gray-600 dark:text-gray-400">
              <div class="mx-auto flex flex-col items-center space-x-6">
                <div class="shrink-0 p-1">
                  @if (file_exists($profile_photo_path_old))
                    <img style="width: 135px; height: 145px;" class="object-center object-fill" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($profile_photo_path_old))) }}" alt="Profile Photo" />
                  @else
                    <img style="width: 135px; height: 145px;" class="object-center object-fill" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/no-img.png'))) }}" alt="Profile Photo" />
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-span-1 m-5 rounded-md border bg-white dark:border-primary-darker dark:bg-darker">
          <div class="flex items-center justify-between border-b p-2 dark:border-primary">
            <h6 class="text-md font-semibold text-gray-500 dark:text-light">Uploaded Student Sign</h6>
          </div>
          <div class="relative h-auto p-4">
            <div class="p-5 text-sm text-gray-600 dark:text-gray-400">
              <div class="mx-auto flex flex-col items-center space-x-6">
                <div class="shrink-0 py-3 bg-white">
                  @if (file_exists($sign_photo_path_old))
                    <img style="width: 200px; height:82px;"class="object-center object-fill" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($sign_photo_path_old))) }}" alt="Profile Photo" />
                  @else
                    <img style="width: 200px; height:82px;" class="object-center object-fill" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/no-img.png'))) }}" alt="Profile Photo" />
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Student Address
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="col-span-1 m-5 rounded-md border bg-white dark:border-primary-darker dark:bg-darker">
          <div class="flex items-center justify-between border-b p-2 dark:border-primary">
            <h5 class="text-lg font-semibold text-gray-500 dark:text-light">Correspondence Address</h5>
          </div>
          <div class="relative h-auto p-4">
            @php
              $tal = App\Models\Taluka::find($taluka_id);
            @endphp
            @if (isset($tal->id))
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="country" :value="__('Country')" />
                <x-input-show id='country' :value="$tal->district->state->country->country_name" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="state" :value="__('State')" />
                <x-input-show id='state' :value="$tal->district->state->state_name" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="district" :value="__('District')" />
                <x-input-show id='district' :value="$tal->district->district_name" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="taluka" :value="__('Tauka')" />
                <x-input-show id='taluka' :value="$tal->taluka_name" />
              </div>
            @endif
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
              <x-input-label for="village" :value="__('Village')" />
              <x-input-show id='village' :value="$village" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
              <x-input-label for="pincode" :value="__('Pincode')" />
              <x-input-show id='pincode' :value="$pincode" />
            </div>
            <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
              <x-input-label for="address" :value="__('Address')" />
              <x-textarea-show id='address' :value="$address" />
            </div>
          </div>
        </div>
        <div class="col-span-1 m-5 rounded-md border bg-white dark:border-primary-darker dark:bg-darker">
          <div class="flex items-center justify-between border-b p-2 dark:border-primary">
            <h5 class="text-lg font-semibold text-gray-500 dark:text-light">Permanent Address</h5>
          </div>
          <div class="relative h-auto p-4">
            @php
              $tal2 = App\Models\Taluka::find($taluka_id_2);
            @endphp
            @if (isset($tal2->id))
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="country_2" :value="__('Country')" />
                <x-input-show id='country_2' :value="$tal2->district->state->country->country_name" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="state_2" :value="__('State')" />
                <x-input-show id='state_2' :value="$tal2->district->state->state_name" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="district_2" :value="__('District')" />
                <x-input-show id='district_2' :value="$tal2->district->district_name" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="taluka_2" :value="__('Taluka')" />
                <x-input-show id='taluka_2' :value="$tal2->taluka_name" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="village_2" :value="__('Village')" />
                <x-input-show id='village_2' :value="$village_2" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="pincode_2" :value="__('Pincode')" />
                <x-input-show id='pincode_2' :value="$pincode_2" />
              </div>
              <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                <x-input-label for="address_2" :value="__('Address')" />
                <x-textarea-show id='address_2' :value="$address_2" />
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        New Course Enrollment
      </div>
      <div class="grid grid-cols-1">
        @php
          $ptn_class = App\Models\Patternclass::find($pattern_class_id);
        @endphp
        @if (isset($ptn_class->id))
          <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
            <x-input-label for="pattern" :value="__('Pattern Class')" />
            <x-input-show id='pattern' :value="get_pattern_class_name($ptn_class->id)" />
          </div>
        @endif
      </div>
    </div>
    <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
      <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
        Previous Exam Details
      </div>
      <div class="p-2 overflow-x-scroll">
        <x-table.table>
          <x-table.thead>
            <x-table.tr>
              <x-table.th>No </x-table.th>
              <x-table.th>Board / University </x-table.th>
              <x-table.th>Course Name </x-table.th>
              <x-table.th>Seat No</x-table.th>
              <x-table.th>Passout Month - Year</x-table.th>
              <x-table.th>Percentage / CGPA</x-table.th>
            </x-table.tr>
          </x-table.thead>
          <x-table.tbody>
            @foreach ($pre_eductions as $key => $item)
              <x-table.tr wire:key="{{ $item->id }}">
                <x-table.td>{{ $key + 1 }}</x-table.td>
                <x-table.td>{{ $item->boarduniversity->boarduniversity_name }} </x-table.td>
                <x-table.td> {{ $item->educationalcourse->course_name }} </x-table.td>
                <x-table.td> {{ $item->seat_number }} </x-table.td>
                <x-table.td> {{ $item->passing_month }} - {{ date(strtotime($item->passing_year)) ?? ' ' }} </x-table.td>
                <x-table.td> {{ $item->percentage ?? '0.00' }} % / {{ $item->cgpa ?? '0.00' }} </x-table.td>
              </x-table.tr>
            @endforeach
          </x-table.tbody>
        </x-table.table>
      </div>
    </div>
  </section>
</div>
