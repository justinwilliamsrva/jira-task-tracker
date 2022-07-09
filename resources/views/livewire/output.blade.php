<div class="flex flex-col space-y-6 py-2">
    <h1 class="text-center text-2xl">Output</h1>
    <div class="flex flex-col space-y-2">
        @foreach($tasks as $key => $task)
            <div class="border-2">
                <div class="flex space-x-2">
                    <h2>{{$key}}</h2>
                    <p>{{$this->formatTimeBy30($task['stats'])}}</p>
                </div>
                @foreach($task['tasks'] as $t)
                    <div class="ml-8">
                        <p>{{$t['time']}} - {{$t['work']}}</p>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>



</div>
