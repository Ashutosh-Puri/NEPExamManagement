@props([ 'slot'=>false])

<select   {!! $attributes->merge(['class' => ' scrollbar-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary dark:focus:border-primary focus:ring-primary dark:focus:ring-primary rounded-md shadow-sm dark:border-primary-darker border']) !!}>
   {{$slot}}
</select>