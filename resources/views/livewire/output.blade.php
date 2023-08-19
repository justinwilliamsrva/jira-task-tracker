<div class="flex flex-col justify-center space-y-6 py-2">
    <!-- Flash message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert" id="flash-message">
            <p class="font-bold">Success</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert" id="flash-message">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif
    <h1 class="text-center text-2xl">View Time</h1>
    <div class="py-2 px-4 text-center @if($jiraStatus) bg-green-300 @else bg-red-300 @endif">{{ ($jiraStatus) ? 'Jira is working' : 'Jira is not working' }}</div>
    <h2 class="text-center text-xl">Total Time<span>{{$tasks ? ' - '.$totalTime : '' }}</span></h2>
    <div class="flex flex-col justify-center">
        <h2 class="text-center text-xl">Jira Link</span></h2>
        <div class="flex flex-col sm:flex-row justify-center space-y-2 space-x-0 sm:space-x-2 sm:space-y-0 mx-auto">
            <input type="text" class="border-2 py-2" wire:model="link" placeholder="{{$placeholder}}">
            <button wire:click="save()" class="py-2 px-8 border-2 border-blue-200 bg-blue-200 hover:bg-blue-500">Save Link</button>
        </div>
    </div>
    <div class="flex flex-col justify-center">
        <h2 class="text-center text-xl">Format</span></h2>
        <span class="isolate inline-flex justify-center rounded-md">
            <button type="button" wire:click="formatTasks('time')" class="{{$taskFormat == 'time' ? 'z-10 border-blue-500 outline-none ring-1 ring-blue-500' :''}} relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">Time</button>
            <button type="button" wire:click="formatTasks('task')" class="{{$taskFormat == 'task' ? 'z-10 border-green-500 outline-none ring-1 ring-green-500' :''}} relative -ml-px inline-flex items-center rounded-r-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">Task</button>
        </span>
    </div>
    @if($taskFormat == 'task')
        @if($tasks)
            <div class="flex flex-col space-y-2 divide-y-2">
                <div>
                    <h1 class="text-center text-xl">Needs to be logged in Jira</h1>
                    @forelse($tasks['incomplete'] as $key => $task)
                        <div class="p-2">
                            <div>
                                <h2 class="text-lg">
                                    <a onclick="confirm('Do you also want to log this task?') || event.stopImmediatePropagation()" wire:click="logTask('{{$key}}')" class="{{$realLink ? 'underline text-blue-500' : 'cursor-default pointer-events-none'}} bold" href="{{$realLink}}{{$key}}" target="_blank">{{$key}}</a>
                                    - <span title="Copy" id="task-incom-time-{{$loop->iteration}}" class="time cursor-pointer border-2 border-blue-200 bg-blue-200 hover:bg-blue-500 hover:border-blue-500 px-2 bold rounded" data-clipboard-target="#task-incom-time-{{$loop->iteration}}"> {{$this->formatTimeBy30($task['stats'])}}</span>
                                    - <button class="btn bg-blue-200 hover:bg-blue-500 px-2 bold rounded" data-clipboard-target="#task-incom-task-{{$loop->iteration}}">Copy<span>&#8595</span></button>
                                    - <button class="bg-green-200 hover:bg-green-500 px-2 bold rounded" onclick="confirm('Are you sure you want to log task {{$key}} ?') || event.stopImmediatePropagation()" wire:click="logTask('{{$key}}')">Log</button>
                                </h2>
                            </div>
                            <ul id="task-incom-task-{{$loop->iteration}}" class="ml-10 list-disc">
                                @foreach($task['taskList'] as $k => $t)
                                    <li>{{$k}} - {{$this->formatTimeBy30($t)}}</li>
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
                                        - <span title="Copy" id="task-com-time-{{$loop->iteration}}" class="time cursor-pointer border-2 border-blue-200 bg-blue-200 hover:bg-blue-500 hover:border-blue-500 px-2 bold rounded" data-clipboard-target="#task-com-time-{{$loop->iteration}}"> {{$this->formatTimeBy30($task['stats'])}}</span>
                                        - <button class="btn bg-blue-200 hover:bg-blue-500 px-2 bold rounded" data-clipboard-target="#task-com-task-{{$loop->iteration}}">Copy<span>&#8595</span></button>
                                        <!-- - <button class="bg-red-200 hover:bg-red-500 px-2 bold rounded" onclick="confirm('Are you sure you want to unlog task {{$key}} ?') || event.stopImmediatePropagation()" wire:click="unLogTask('{{$key}}')">Unlog</button> -->
                                    </h2>
                                </div>
                                <ul id="task-com-task-{{$loop->iteration}}"class="ml-10 list-disc">
                                @foreach($task['taskList'] as $k => $t)
                                    <li>{{$k}} - {{$this->formatTimeBy30($t)}}</li>
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
    @elseif($taskFormat == 'time')
        @if($tasks)
            <div class="flex flex-col space-y-2 divide-y-2">
                <div>
                    <h1 class="text-center text-xl">Needs to be logged in Jira</h1>
                    @forelse($tasks['incomplete'] as $key => $task)
                        <div class="p-2">
                            <div>
                                <h2 class="text-lg">
                                    <a onclick="confirm('Do you also want to log this task?') || event.stopImmediatePropagation()" wire:click="logTask('{{$key}}')" class="{{$realLink ? 'underline text-blue-500' : 'cursor-default pointer-events-none'}} bold" href="{{$realLink}}{{$key}}" target="_blank">{{$key}}</a>
                                    - <span title="Copy" id="incom-time-{{$loop->iteration}}" class="time cursor-pointer border-2 border-blue-200 bg-blue-200 hover:bg-blue-500 hover:border-blue-500 px-2 bold rounded" data-clipboard-target="#incom-time-{{$loop->iteration}}"> {{$this->formatTimeBy30($task['stats'])}}</span>
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
                                        - <span title="Copy" id="com-time-{{$loop->iteration}}" class="time cursor-pointer border-2 border-blue-200 bg-blue-200 hover:bg-blue-500 hover:border-blue-500 px-2 bold rounded" data-clipboard-target="#com-time-{{$loop->iteration}}"> {{$this->formatTimeBy30($task['stats'])}}</span>
                                        - <button class="btn bg-blue-200 hover:bg-blue-500 px-2 bold rounded" data-clipboard-target="#com-task-{{$loop->iteration}}">Copy<span>&#8595</span></button>
                                        <!-- - <button class="bg-red-200 hover:bg-red-500 px-2 bold rounded" onclick="confirm('Are you sure you want to unlog task {{$key}} ?') || event.stopImmediatePropagation()" wire:click="unLogTask('{{$key}}')">Unlog</button> -->
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
    @endif
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    setTimeout(function() {
        $('#flash-message').fadeOut('fast');
    }, 5000); // 5 seconds
</script>