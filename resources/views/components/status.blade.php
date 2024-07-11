@props(['slot' =>false , 'type' =>false])

@if ($type=='info')
    <span {!! $attributes->merge(['class' => 'cursor-pointer inline-flex items-center  text-white text-sm font-medium px-2.5 py-0.5 rounded-md bg-info']) !!}>
        {{ $slot }}
    </span>
@elseif($type=='success')
    <span {!! $attributes->merge(['class' => 'cursor-pointer inline-flex items-center  text-white text-sm font-medium px-2.5 py-0.5 rounded-md bg-success']) !!}>
        {{ $slot }}
    </span>
@elseif($type=='warning')
    <span {!! $attributes->merge(['class' => 'cursor-pointer inline-flex items-center  text-white text-sm font-medium px-2.5 py-0.5 rounded-md bg-warning']) !!}>
        {{ $slot }}
    </span>
@elseif($type=='danger')
    <span {!! $attributes->merge(['class' => 'cursor-pointer inline-flex items-center  text-white text-sm font-medium px-2.5 py-0.5 rounded-md bg-danger']) !!}>
        {{ $slot }}
    </span>
@else
    <span {!! $attributes->merge(['class' => 'cursor-pointer inline-flex items-center  text-white text-sm font-medium px-2.5 py-0.5 rounded-md bg-primary']) !!}>
        {{ $slot }}
    </span>
@endif