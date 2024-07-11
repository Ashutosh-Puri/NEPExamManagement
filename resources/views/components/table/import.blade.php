@props(["disabled" => false])
<div class="float-right mx-1 inline-flex h-10 z-0 ">
  <div class="relative inline-flex h-10 flex-wrap items-stretch z-0">
    <form class="relative inline-flex" wire:submit.prevent="import()">
    <input {{ $disabled }}  accept=".xlsx, .csv" type="file" wire:model="importfile" class="z-0  w-60 bg-white relative block flex-auto rounded-l h-10 text-sm file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-semibold dark:file:bg-primary file:text-white dark:hover:file:bg-primary-darker hover:file:bg-primary-darker file:bg-primary   border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm dark:border-primary-darker border " />
    <button type="submit"  {{ $disabled }} wire:loading.attr="disabled" class="z-0 hover:bg-primary-600 focus:bg-primary-600 active:bg-primary-700 inline-block h-10 rounded-r bg-primary px-3 pb-2 pt-2.5 text-xs font-medium leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-ripple-init type="button" id="button-addon2">
      <span>Import</span>
    </button>
    @error('importfile')
      <span class=" absolute block left-28 top-10 mb-2 text-red-500 text-xs">{{ $message }}</span>
    @enderror
  </form>
  </div>
</div>