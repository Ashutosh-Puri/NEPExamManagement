@props(["disabled" => false, "slot" => false])

<tbody {{ $disabled ? "disabled" : "" }} {!! $attributes->merge(["class" => "divide-y divide-gray-300 dark:divide-primary-darker"]) !!}>
  {{ $slot }}
</tbody>
