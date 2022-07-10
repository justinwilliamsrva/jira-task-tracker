<div class="flex flex-col space-y-6 py-2">
    <h1 class="text-center text-2xl">View Time</h1>
    @if($tasks)
    <div class="flex flex-col space-y-2 divide-y-2">

        <div>
            <h1 class="text-center text-xl">Needs to be logged in Jira</h1>
            @forelse($tasks['incomplete'] as $key => $task)
            <div class="p-2">
                <div>
                    <h2 class="text-md">#DEV-{{$key}}  |  {{$this->formatTimeBy30($task['stats'])}}</h2>
                </div>
                @foreach($task['tasks'] as $t)
                <div class="ml-10">
                    <p>{{$t['time']}} - {{$t['work']}}</p>
                </div>
                @endforeach
            </div>
            @empty
            <div class="text-lg text-center">-Add Some Time-</div>
            @endforelse
        </div>

        <div>
            <h1 class="text-center text-xl">Already logged in Jira</h1>
            @forelse($tasks['completed'] as $key => $task)
            <div class="p-2">
                <div>
                <h2 class="text-md">#DEV-{{$key}}  |  {{$this->formatTimeBy30($task['stats'])}}</h2>

                </div>
                @foreach($task['tasks'] as $t)
                <div class="ml-10">
                    <p>{{$t['time']}} - {{$t['work']}}</p>
                </div>
                @endforeach
                @empty
                <div class="text-lg text-center">-Add Some Time-</div>
                @endforelse
            </div>
        </div>
        @else
        <div class="text-lg text-center">-Add Some Time-</div>

        @endif


    </div>