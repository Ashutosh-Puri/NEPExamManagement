@props(["disabled" => false, "slot" => false])

<table {{ $disabled ? "disabled" : "" }} {!! $attributes->merge(["class" => "min-w-full divide-y divide-gray-200 dark:divide-gray-700 border dark:border-primary-darker dark:bg-darker "]) !!}>
  {{ $slot }}
</table>