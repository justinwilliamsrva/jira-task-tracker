<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Jira Task Tracker</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->

    @vite('resources/js/app.js')
    @livewireStyles
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="antialiased">
    <div>
        <div class="container max-w-7xl mx-auto mt-10 space-y-4">
            <h1 class=" text-center text-5xl px-4">Jira Task Tracker</h1>
            <div id="change-page" class="ml-2 z-50 lg:relative">
                <a class="{{ Route::currentRouteName() ==  'input' ? 'hidden' : 'py-2 px-8 bg-blue-200 hover:bg-blue-500'}}" href="{{route('input')}}"><button >Add Time</button></a>
                <a class="{{ Route::currentRouteName() ==  'output' ? 'hidden' : 'py-2 px-8 bg-green-200 hover:bg-green-500'}}"href="{{route('output')}}"><button>View Time</button></a>
            </div>
            <div class="">
               {{ $slot }}
            </div>
        </div>
    </div>
    @livewireScripts
</body>
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
<script type="text/javascript">
        var Clipboard = new ClipboardJS('.btn');
        var clipboard = new ClipboardJS('.time');
</script>
<script>
window.onscroll = function() {
    var changePage = document.getElementById("change-page");
    if (window.pageYOffset > 80 && screen.width >= 380) {
        changePage.classList.add("fixed", "top-0.5");
    } else if (window.pageYOffset > 135 && screen.width < 380) {
    changePage.classList.add("fixed", "top-0.5");
    } else {
        changePage.classList.remove("fixed", "top-0.5");
    }
}
</script>
</html>