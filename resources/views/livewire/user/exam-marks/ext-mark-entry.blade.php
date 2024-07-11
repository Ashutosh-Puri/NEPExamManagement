 <div>
   <x-breadcrumb.breadcrumb>
     <x-breadcrumb.link route="user.dashboard" name="Dashboard" />
     <x-breadcrumb.link name="Marks Entry" />
   </x-breadcrumb.breadcrumb>
   <x-card-header heading="Marks Entry" />
   <div class="m-2 overflow-hidden rounded border bg-white shadow dark:border-primary-darker dark:bg-darker">
     <div class="bg-primary px-2 py-2 font-semibold text-white dark:text-light">
       Marks Entry
     </div>
     <section>
       <div class="grid grid-cols-1 md:grid-cols-3">
         <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
           <x-input-label for="scanbarcode" :value="__('Scan Barcode')" />
           <x-text-input id="scanbarcode" type="text" wire:model.live="scanbarcode" x-ref="scanbarcode" placeholder="{{ __('Scan Barcode') }}" name="scanbarcode" class="w-full mt-1" :value="old('scanbarcode', $scanbarcode)" autofocus autocomplete="scanbarcode" />
           <x-input-error :messages="$errors->get('scanbarcode')" class="mt-1" />
         </div>
         @if ($modify)
           @if ($examiner_name)
             <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
               <x-input-label for="examiner_name" :value="__('Examiner')" />
               <x-input-show id="examiner_name" :value="$examiner_name" />
             </div>
           @endif
           @if ($moderator_name)
             <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
               <x-input-label for="moderator_name" :value="__('Moderator')" />
               <x-input-show id="moderator_name" :value="$moderator_name" />
             </div>
           @endif
         @endif
       </div>
     </section>
     @if ($modify)
       <section x-data="{ show: false }">
         @if ($showFlag && $scanbarcode)
           <div class="w-ful">
             <div class="grid grid-cols-1 md:grid-cols-3">
               <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                 <x-input-label for="barcode" :value="__('Barcode')" />
                 <x-input-show id="barcode" :value="$barcode" />
               </div>
               <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                 <x-input-label for="examiner" :value="__('Enter Examiner Marks')" />
                 <x-text-input id="examiner" type="text" wire:model="examiner" placeholder="Enter Examiner's Marks" name="examiner" class="w-full mt-1" :value="old('examiner', $examiner)" autocomplete="examiner" />
                 <x-input-error :messages="$errors->get('examiner')" class="mt-1" />
               </div>
               <div class="px-5 py-2 text-sm text-gray-600 dark:text-gray-400">
                 <x-input-label for="moderator" :value="__('Enter Moderator Marks')" />
                 <x-text-input id="moderator" type="text" wire:model="moderator" placeholder="Enter Moderator's Marks" name="moderator" class="w-full mt-1" :value="old('moderator', $moderator)" autocomplete="moderator" />
                 <x-input-error :messages="$errors->get('moderator')" class="mt-1" />
               </div>
               <div></div>
               <div></div>
               <div>
                 <x-primary-button class=" m-3 float-end block" wire:loading.attr="disabled" wire:click="addmarks" @click="$refs.scanbarcode.focus()"> Add Marks</x-primary-button>
               </div>
             </div>
           </div>
         @endif
         @if ($paperassesments)
           <div class=" flex overflow-x-scroll">
             <x-table.table>
               <x-table.thead>
                 <x-table.tr>
                   <x-table.th>#</x-table.th>
                   <x-table.th>Lot Number</x-table.th>
                   <x-table.th>Subject Code and Name</x-table.th>
                   <x-table.th>Barcode No.</x-table.th>
                   <x-table.th>Examiner Marks</x-table.th>
                   <x-table.th>Moderator Marks</x-table.th>
                 </x-table.tr>
               </x-table.thead>
               <x-table.tbody>
                 @foreach ($paperassesments as $paperassesment)
                   <x-table.tr wire:key="{{ $paperassesment->id }}">
                     <x-table.td>{{ $loop->iteration }} </x-table.td>
                     <x-table.td>{{ $paperassesment->paperassesment_id }} </x-table.td>
                     <x-table.td>{{ $paperassesment->subject->subject_code ?? '' }} {{ $paperassesment->subject->subject_name ?? '' }} </x-table.td>
                     <x-table.td>{{ $paperassesment->id }} </x-table.td>
                     <x-table.td>{{ $paperassesment->examiner_marks }} </x-table.td>
                     <x-table.td>{{ $paperassesment->moderator_marks }} </x-table.td>
                   </x-table.tr>
                 @endforeach
               </x-table.tbody>
             </x-table.table>
           </div>
         @endif
       </section>
     @endif
   </div>
 </div>
