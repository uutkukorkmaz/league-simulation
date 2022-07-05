<!doctype html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{config('app.name')}}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="{{mix('css/app.css')}}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-slate-800 bg-slate-100 text-slate-300 text-slate-800">
<div class="max-w-7xl mx-auto my-6">
    <x-navigation/>
    {{$slot}}
</div>
<script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
<script src="{{ mix('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
