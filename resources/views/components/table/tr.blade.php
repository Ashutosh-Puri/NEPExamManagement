@props(["disabled" => false, "slot" => false])

<tr {{ $disabled ? "disabled" : "" }} {!! $attributes->merge(["class" => ""]) !!}>
  {{ $slot }}
</tr>
