@props([ 'slot'=>false ,'id'=>Str::uuid() , 'name'=>'no_name' ,'live'=>false])

<div wire:ignore>
    <select  id="{{ $id }}"  style="width:100% ;"   {!! $attributes->merge(['class' => '']) !!} >
       {{$slot}}
    </select>
</div>

@push('scripts')
<script>

document.addEventListener('livewire:navigated', () => {
    if ($('#{{ $id }}').data('select2')) {
    $('#{{ $id }}').select2('destroy');
}
        $('#{{ $id }}').select2({
            templateSelection: function (data) 
            {
                if (data.id === '') 
                {
                    return '-- Select One Option --';
                }
                return data.text;
            }, placeholder: "Select Many Options"
        });
        $('#{{ $id }}').on('change', function (e) {
            var data = $('#{{ $id }}').select2("val");
        @this.set('{{ $name }}', data,'{{ $live }}');
        });
});
</script>   
@endpush
