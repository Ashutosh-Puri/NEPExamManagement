@props(['header' => false, 'mode' => 'all', 'sw' => 20, 'sp' => true, 'r' => true, 'body' => false, 'footer' => false, 'title' => false, 'i' => false, 'x' => true, 'p' => true, 's' => true, 'a' => true])
<div class="m-2 flex flex-col">
  <div class="-m-1.5 overflow-x-auto">
    <div class="inline-block min-w-full p-1.5 align-middle">
      <div class="overflow-hidden">
        <div class="flex flex-col">
          <div class="-m-1.5 overflow-x-auto">
            <div class="inline-block min-w-full p-1.5 align-middle">
              <div class="  divide-y divide-gray-300 rounded-lg border bg-gray-200 dark:divide-primary-darker dark:border-primary-darker dark:bg-transparent">
                @if ($a)
                  <div class="p-1 flex gap-x-0.5">
                    <x-table.loading />
                    @if ($p)
                      <x-table.perpage />
                    @endif
                    @if ($s)
                      <x-table.search :width="$sw" />
                    @endif
                    @if ($header)
                      <div class="flex">
                        {{ $header }}
                      </div>
                    @endif
                    <div class="flex mx-auto">
                    </div>
                    @if ($x)
                      <x-table.export />
                    @else
                      @if ($sp)
                        <x-table.spinner />
                      @endif
                    @endif
                    @if ($i)
                      @if (isset($mode))
                        @if ($mode == 'all')
                          <x-table.import-btn wire:click="setmode('import')" />
                        @elseif($mode == 'import')
                          <x-table.import-close-btn wire:click="setmode('all')" />
                        @endif
                      @endif
                    @endif
                    @if ($r)
                      <x-table.refresh />
                    @endif
                  </div>
                @endif
                <div class="overflow-hidden">
                  {{ $body }}
                </div>
                @if ($footer)
                  {{ $footer }}
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
