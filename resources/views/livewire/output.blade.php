<div class="flex flex-col space-y-6 py-2">
    <h1 class="text-center text-2xl">View Time</h1>
    <h2 class="text-center text-xl">Total Time<span>{{$tasks ? ' - '.$totalTime : '' }}</span></h2>
    <flex class="flex flex-col sm:flex-row justify-center space-y-2 space-x-0 sm:space-x-2 sm:space-y-0 mx-auto">
        <input type="text" class="border-2 py-2" wire:model="link" placeholder="{{$placeholder}}">
        <button wire:click="save()" class="py-2 px-8 border-2 border-blue-200 bg-blue-200 hover:bg-blue-500">Save Link</button>
    </flex>
    @if($tasks)
        <div class="flex flex-col space-y-2 divide-y-2">
            <div>
                <h1 class="text-center text-xl">Needs to be logged in Jira</h1>
                @forelse($tasks['incomplete'] as $key => $task)
                    <div class="p-2">
                        <div>
                            <h2 class="text-lg">
                                <a class="{{$realLink ? 'underline text-blue-500' : 'cursor-default pointer-events-none'}} bold" href="{{$realLink}}{{$key}}" target="_blank">{{$key}}</a>
                                - <span title="Copy" id="incom-time-{{$loop->iteration}}" class="time cursor-pointer px-2 border-2 hover:border-blue-200 hover:shadow hover:bg-blue-200 bold rounded" data-clipboard-target="#incom-time-{{$loop->iteration}}"> {{$this->formatTimeBy30($task['stats'])}}</span>
                                - <button class="btn bg-blue-200 hover:bg-blue-500 px-2 bold rounded" data-clipboard-target="#incom-task-{{$loop->iteration}}">Copy<span>&#8595</span></button>
                                - <button class="bg-green-200 hover:bg-green-500 px-2 bold rounded" onclick="confirm('Are you sure you want to log task {{$key}} ?') || event.stopImmediatePropagation()" wire:click="logTask('{{$key}}')">Log</button>
                            </h2>
                        </div>
                        <ul id="incom-task-{{$loop->iteration}}" class="ml-10 list-disc">
                            @foreach($task['tasks'] as $t)
                                @if ($t['fifteen'])
                                    <li>{{$t['time']}} - 15: {{$t['work']}}</li>
                                @else
                                    <li>{{$t['time']}} - {{$t['work']}}</li>
                                @endif
                            @endforeach
                        </ul>
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
                                <h2 class="text-lg"><a class="{{$realLink ? 'underline text-blue-500' : 'cursor-default pointer-events-none'}} bold" href="{{$realLink}}{{$key}}" target="_blank">{{$key}}</a>
                                    - <span title="Copy" id="com-time-{{$loop->iteration}}" class="time cursor-pointer px-2 border-2 hover:border-blue-200 hover:shadow hover:bg-blue-200 bold rounded" data-clipboard-target="#com-time-{{$loop->iteration}}"> {{$this->formatTimeBy30($task['stats'])}}</span>
                                    - <button class="btn bg-blue-200 hover:bg-blue-500 px-2 bold rounded" data-clipboard-target="#com-task-{{$loop->iteration}}">Copy<span>&#8595</span></button>
                                    - <button class="bg-red-200 hover:bg-red-500 px-2 bold rounded" onclick="confirm('Are you sure you want to unlog task {{$key}} ?') || event.stopImmediatePropagation()" wire:click="unLogTask('{{$key}}')">Unlog</button>
                                </h2>
                            </div>
                            <ul id="com-task-{{$loop->iteration}}"class="ml-10 list-disc">
                                @foreach($task['tasks'] as $t)
                                    @if ($t['fifteen'])
                                        <li>{{$t['time']}} - 15: {{$t['work']}}</li>
                                    @else
                                        <li>{{$t['time']}} - {{$t['work']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <div class="text-lg text-center">-Add Some Time-</div>
                    @endforelse
            </div>
        @else
            <div class="text-lg text-center">-Add Some Time-</div>

        @endif


    </div>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
    <script type="text/javascript">
        var Clipboard = new ClipboardJS('.btn');
        var clipboard = new ClipboardJS('.time');
    </script>