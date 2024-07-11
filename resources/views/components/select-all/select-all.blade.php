<div x-data="select_all">

    <input  type="checkbox"  @change="handelCheck"   {!! $attributes->merge(['class' => 'my-1  h-6 w-6 ml-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-primary focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm dark:border-primary-darker border']) !!}>
</div>

@push('scripts')
    
<script>
    Alpine.data('select_all',()=>{

        return {

            handelCheck(e){
                e.target.checked ? this.selectAll() :this.deselectAll()


                if(el.checked)
                {
                    // select all
                }
            },
            selectAll()
            {
                this.$wire.question_bank.push('1');
            },
            deselectAll()
            {

            },
        }
 
    });
</script>
@endpush
