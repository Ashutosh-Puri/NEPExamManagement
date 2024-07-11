@props(['paginator', 'prev' => false])

@if ($paginator->isEmpty())
@else
    <div class="hidden sm:flex-1 sm:flex px-2 sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-black dark:text-white leading-5">
                {!! __('Showing') !!}
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                {!! __('to') !!}
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                {!! __('of') !!}
                <span class="font-medium">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>
        </div>
        <div>
            <span class="relative z-0 inline-flex shadow-sm rounded-md m-4">
                <ul class="flex justify-between">
                    @if ($prev)
                    @else
                        @if ($paginator->onFirstPage())
                            <li class="mr-2 relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-black dark:text-white bg-primary hover:bg-primary-darker dark:bg-primary-dark border border-transparent rounded-md dark:text-primary-800 dark:hover:bg-primary-darker focus:bg-primary-darker focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 dark:border-primary-darker cursor-not-allowed leading-5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
                                </svg>
                                Prev
                            </li>
                        @else
                            <li wire:click="previousPage" class="mr-2 relative cursor-pointer inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-black dark:text-white bg-primary hover:bg-primary-darker dark:bg-primary-dark border border-transparent rounded-md dark:text-primary-800 dark:hover:bg-primary-darker focus:bg-primary-darker focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 dark:border-primary-darker leading-5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
                                </svg>
                                Prev
                            </li>
                        @endif
                    @endif
                    @if ($paginator->hasMorePages())
                        <li wire:click="nextPage" class="cursor-pointer hover:bg-gray-200 relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-black dark:text-white bg-primary hover:bg-primary-darker dark:bg-primary-dark border border-transparent rounded-md dark:text-primary-800 dark:hover:bg-primary-darker focus:bg-primary-darker focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 dark:border-primary-darker leading-5">
                            Save & Next
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 ml-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                            </svg>
                        </li>
                    @else
                        <li wire:click="finishPage" class="cursor-pointer hover:bg-gray-200 relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-black dark:text-white bg-primary hover:bg-primary-darker dark:bg-primary-dark border border-transparent rounded-md dark:text-primary-800 dark:hover:bg-primary-darker focus:bg-primary-darker focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 dark:border-primary-darker leading-5">Finish</li>
                    @endif
                </ul>
            </span>
        </div>
    </div>
@endif
