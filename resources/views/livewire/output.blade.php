<div class="flex flex-col space-y-6 py-2">
    <h1 class="text-center text-2xl">Output</h1>
    <div class="flex flex-col space-y-2">
        @foreach($tasks as $key => $task)
            <div class="border-2">
                <h2>{{$key}}</h2>
                @foreach($task as $t)
                    <div class="flex justify-around">
                        <p>{{$t['time']}}</p>
                        <p>{{$t['work']}}</p>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>



</div>
