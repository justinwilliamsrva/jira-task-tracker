<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles


    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body class="antialiased">


    <div>
        <div class="container max-w-5xl mx-auto mt-10 space-y-4">
            <h1 class=" text-5xl">Jira Tracker</h1>
            <div class="flex space-x-3 mx-auto">
                <a href="{{route('input')}}"><button class="py-2 px-8 bg-blue-200">Input</button> </a>
                <a href="{{route('output')}}"><button class="py-2 px-8 bg-green-200">Output</button> </a>

            </div>


            <div class="border-2">
               {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>