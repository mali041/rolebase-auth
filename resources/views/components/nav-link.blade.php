@props(['active' => false])
<a 
    class="{{ $active ? 'text-pink-900 border-pink-900 border-l px-2' : 'text-gray-400 hover:text-pink-600'}} mr-4 text-sm font-semibold" 
    aria-current="{{ $active ? 'page': 'false' }}"
    {{ $attributes }} 
>
    {{ $slot }}
</a>