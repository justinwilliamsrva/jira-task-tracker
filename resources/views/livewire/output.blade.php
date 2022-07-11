<div class="flex flex-col space-y-6 py-2">
    <h1 class="text-center text-2xl">View Time</h1>
    <flex class="flex flex-col sm:flex-row justify-center space-y-2 space-x-0 sm:space-x-2 sm:space-y-0 mx-auto">
        <input type="text" class="border-2 py-2" wire:model="link" placeholder="{{$placeholder}}">
        <button wire:click="save()" class="py-2 px-8 border-2 border-blue-200 bg-blue-200">Save Link</button>
    </flex>
    @if($tasks)
    <div class="flex flex-col space-y-2 divide-y-2">

        <div>
            <h1 class="text-center text-xl">Needs to be logged in Jira</h1>
            @forelse($tasks['incomplete'] as $key => $task)
            <div class="p-2">
                <div>
                    <h2 class="text-md"><a href="{{$realLink}}{{$key}}"  target="_blank">{{$key}}</a>  |  {{$this->formatTimeBy30($task['stats'])}}</h2>
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
                    <h2 class="text-md"><a href="{{$realLink}}{{$key}}"  target="_blank">{{$key}}</a>  |  {{$this->formatTimeBy30($task['stats'])}}</h2>
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