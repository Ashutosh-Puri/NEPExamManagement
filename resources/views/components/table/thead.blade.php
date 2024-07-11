@props(["disabled" => false, "slot" => false])

<thead {{ $disabled ? "disabled" : "" }} {!! $attributes->merge(["class" => "bg-primary-darker text-white"]) !!}>
  {{ $slot }}
</thead>
