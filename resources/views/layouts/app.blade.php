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
            <h1 class=" text-center text-5xl">Jira Task Tracker</h1>
            <div class="ml-2">
                <a class="{{ Route::currentRouteName() ==  'input' ? 'hidden' : 'py-2 px-8 bg-blue-200 hover:bg-blue-500'}}" href="{{route('input')}}"><button >Add Time</button> </a>
                <a class="{{ Route::currentRouteName() ==  'output' ? 'hidden' : 'py-2 px-8 bg-green-200 hover:bg-green-500'}}"href="{{route('output')}}"><button>View Time</button> </a>

            </div>


            <div class="">
               {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>