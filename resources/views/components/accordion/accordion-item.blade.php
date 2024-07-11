
 @props(['slot' => false, 'tab' => false, 'title' => false])
 <h2 id="accordion-flush-heading-{{ $tab }}">
   <button @click="openTab === {{ $tab }} ? openTab = null : openTab = {{ $tab }}" type="button" class="flex items-center justify-between w-full py-2 font-medium rtl:text-right text-gray-800 border-b border-gray-200 dark:border-gray-700 dark:text-white gap-3" :aria-expanded="openTab === {{ $tab }} ? 'true' : 'false'" :aria-controls="'accordion-flush-body-{{ $tab }}'">
     <div class="overflow-hidden">
       <span class="truncate inline-block max-w-full font-bold">{{ $title }}</span>
     </div>
     <svg data-accordion-icon class="w-3 h-3 transition-transform" :class="{ 'rotate-0': openTab === {{ $tab }}, 'rotate-180': openTab !== {{ $tab }} }" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
       <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
     </svg>
   </button>
 </h2>
 <div id="accordion-flush-body-{{ $tab }}" x-show="openTab === {{ $tab }}" aria-labelledby="accordion-flush-heading-{{ $tab }}">
   <span class="font-bold">{{ $title }}</span>
   <hr class="border-dashed border-1 mt-2 border-primary-lighter" >

   <div class="py-5 border-b border-gray-200 dark:border-gray-700">
     {{ $slot }}
   </div>
 </div>
 