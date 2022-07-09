<div class="flex flex-col space-y-6 py-2">
    <h1 class="text-center text-2xl">Output</h1>
    @if($tasks)
    <div class="flex flex-col space-y-2">

        <div>
            <h1 class="text-center text-xl">Needs to be logged in Jira</h1>
            @forelse($tasks['incomplete'] as $key => $task)
                <div class="border-b-2">
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
            @empty
                <div>All Tasks have already been added to Jira</div>
            @endforelse
        </div>

        <div>
            <h1 class="text-center text-xl">Already logged in Jira</h1>
            @forelse($tasks['completed'] as $key => $task)
                <div class="">
                    <div class="flex space-x-2">
                        <h2>{{$key}}</h2>
                        <p>{{$this->formatTimeBy30($task['stats'])}}</p>
                    </div>
                    @foreach($task['tasks'] as $t)
                        <div class="ml-8">
                            <p>{{$t['time']}} - {{$t['work']}}</p>
                        </div>
                    @endforeach
            @empty
            <div>No Tasks have been added to Jira</div>
            @endforelse
        </div>
    </div>
    @else
    <div>Add some Tasks</div>

    @endif


</div>
