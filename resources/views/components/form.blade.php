@props([ 'slot'=>false ,'action'=>''])
<form  method="post" action="{{ $action }}"   {!! $attributes->merge(['class' => '']) !!}>
   {{$slot}}
</form>