@props(['active','title'])

@php
    $active = $active ?? false;
    $class = [
        $active =>"inline-block p-4 text-blue-600 rounded-t-lg border-b-2 border-blue-600 active text-blue-500 border-blue-500",
        !$active =>"inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 hover:text-gray-300"
]
@endphp

<li class="mr-2">
    <a {{$attributes->merge(['class' => $class[true]])}}
       @if($active) aria-current="page" @endif>{{$title}}</a>
</li>
