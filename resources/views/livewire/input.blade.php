    <div class="flex flex-col space-y-6 py-2">
        <h1 class="text-center text-2xl">Add Time</h1>
        <div class="flex flex-col md:flex-row justify-center space-x-0 space-y-4 md:space-x-4 md:space-y-0">
            <div class="flex-1 first-line:flex flex-col justify-center">
                <h2 class="text-center text-xl">Time</h2>
                <div class="flex justify-center space-x-1 max-w-4xl mx-auto">
                    <div class="flex flex-col">
                        <button class="{{$counter['start'] > 22 ? 'invisible pointer-events-none ' : '' }}" wire:click="increment('start')">
                            <svg xmlns="http://www.w3.org/2000/svg"  class="h-10 w-10 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                            </svg>
                        </button>
                        <button class="{{$counter['start'] < 1 ? 'invisible pointer-events-none ' : '' }}" wire:click="decrement('start')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z" />
                             </svg>
                        </button>
                    </div>
                    <div class="flex flex-col w-24">
                        <label class="text-center">Start Time</label>
                        <p class="text-center text-6xl cursor-pointer" wire:click="getCurrentTime()">{{ $this->showStart()}}<span class="text-base">{{ $counter['start'] < 12 ? 'am' : 'pm'}}</span></p>
                    </div>
                    <div class="flex flex-col w-24">
                        <label class="text-center">End Time</label>
                        <p class="text-center text-6xl">{{$counter['end']}}<span class="text-base">{{$counter['end'] < 12 ? 'pm' : 'am'}}</span></p>
                    </div>
                    <div class="flex flex-col">
                        <button class="{{$counter['end'] > 11 ? 'invisible pointer-events-none ' : '' }}" wire:click="increment('end')">
                            <svg xmlns="http://www.w3.org/2000/svg"  class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" />
                            </svg>
                        </button>
                        <button class="{{$counter['end'] < 2 ? 'invisible pointer-events-none ' : '' }}" wire:click="decrement('end')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z" />
                             </svg>
                        </button>
                    </div>
                </div>
                <div x-data class="flex justify-center space-x-2">
                    <button onclick="confirm('Are you sure you want to clear your time and unlocked tasks ?') || event.stopImmediatePropagation()" wire:click="clear()" class="w-32 py-2 px-8 bg-blue-200">Clear</button>
                    <button onclick="confirm('Are you sure you want to clear everything?') || event.stopImmediatePropagation()" wire:click="clear('All')" class="w-32 py-2 px-8 bg-red-200">Clear All</button>
                </div>
            </div>
            <div class="flex-1 p-2">
                <h2 class="text-center text-xl">Tasks</h2>
                <table class="table-fixed min-w-full p-2">
                    <thead class="text-gray-700 uppercase">
                        <tr class="">
                            <th class="bg-gray-50 w-[15%] text-left"><button class="w-full uppercase bg-gray-200 px-2 rounded hover:bg-gray-300 border border-gray-200 text-center" wire:click="lockAll()">Lock<span>&#8595</span></button></th>
                            <th class="bg-gray-50 w-[28%] text-left">Task #</th>
                            <th class="bg-gray-50 text-left">Task Titles</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @forelse($taskTable as $taskName => $taskTitle)
                            <tr class="border-b">
                                <td class="">
                                    <label class="relative flex items-center justify-start cursor-pointer">
                                        <input type="checkbox" wire:model="taskTable.{{$taskName}}.locked" class="vis-hidden">
                                        <span class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-2 border border-gray-400 rounded shadow w-full text-center">Lock</span>
                                    </label>
                                </td>
                                <td class="text-center flex items-center space-x-1"><button id="taskName-{{$loop->iteration}}" data-clipboard-target="#taskName-{{$loop->iteration}}" class="btn bg-gray-200 border-gray-200 px-2 rounded hover:bg-gray-300" wire:click="copyPasteTask('{{ $taskName }}')"> {{ $taskName }}</button><a href="{{$realLink}}{{$taskName}}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
  <path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.667l3-3z" />
  <path d="M11.603 7.963a.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.667l-3 3a2.5 2.5 0 01-3.536-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 105.656 5.656l3-3a4 4 0 00-.225-5.865z" />
</svg>
</a></td>
                                <td class="text-left"><input class="w-full border-gray-200" type="text" wire:model.debounce.500ms="taskTitles.{{$taskName}}"/></td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="gap-y-4 sm:gap-x-4 sm:gap-y-6 grid sm:grid-cols-2">
            @foreach($timeArray as $time)
                @if(str_contains($time, '00'))
                    <div id="iteration-{{ $loop->iteration }}" x-data="{ open_{{Str::replace('-', '_', Str::slug($time))}}: '{{ !empty($task[Str::replace('00','15',$time)]['task']) }}'}" x-on:clear.window="open_{{Str::replace('-', '_', Str::slug($time))}} = false" class="space-y-2">
                    <div class="bg-blue-400 grid grid-cols-3 gap-y-2 p-2 rounded">
                            <h2 @click="open_{{Str::replace('-', '_', Str::slug($time))}}=!open_{{Str::replace('-', '_', Str::slug($time))}}; $wire.setFifteenMinutes('{{$time}}','00','15')" class=" cursor-pointer col-span-1 p-1 text-center order-1 text-lg">{{ $time }}</h2>
                            <label class="col-span-1 p-1 order-3 text-center">
                                Logged
                                <input type="checkbox" wire:model.debounce.500ms="task.{{$time}}.completed">
                            </label>
                            <div class="col-span-3 order-5 flex justify-around">
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" wire:click="clearSingle('{{$time}}')">Clear</button>
                                <label @click="{{ empty($task[$time]['fifteen'])}} ? open_{{Str::replace('-', '_', Str::slug($time))}} = true : open_{{Str::replace('-', '_', Str::slug($time))}}=open_{{Str::replace('-', '_', Str::slug($time))}}" wire:click="setFifteenMinutes('{{$time}}','00','15')" class="relative flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="task.{{$time}}.fifteen" class="vis-hidden">
                                    <span class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 py-1.5 border border-gray-400 rounded shadow">Fifteen</span>
                                </label>
                                <button :class="{{ $loop->iteration == 1 }} ? 'invisible' : ''" class="bg-white text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" onclick="copyFromAbove({{ $loop->iteration }}, {{ $loop->iteration }})" {{ $loop->iteration == 1 ? 'disabled' : '' }}>Paste<span>&#8595</span></button>
                            </div>
                            <input id="task-{{ $loop->iteration }}" class="{{ $taskForCopying == $time ?'bg-yellow-200':'' }} col-span-1 p-1 order-2" type="text" wire:model.debounce.500ms="task.{{$time}}.task" wire:click="taskToPasteTo('{{$time}}')" placeholder="Task #">
                            <input id="desc-{{ $loop->iteration }}" class="col-span-3 p-1 order-4" wire:model.debounce.500ms="task.{{$time}}.work" placeholder="Work Completed"></input>
                        </div>
                        <div :class="open_{{Str::replace('-', '_', Str::slug($time))}} ? '' : 'hidden'" class="bg-green-400 grid grid-cols-3 gap-y-2 p-2 rounded">
                            <h2 @click="open_{{Str::replace('-', '_', Str::slug($time))}}=!open_{{Str::replace('-', '_', Str::slug($time))}}" class="cursor-pointer col-span-1 p-1 text-center order-1 text-lg">{{ Str::replace('00','15',$time) }}</h2>
                            <label class="col-span-1 p-1 order-3 text-center">
                                Logged
                                <input type="checkbox" wire:model.debounce.500ms="task.{{Str::replace('00','15',$time)}}.completed">
                            </label>
                            <div class="col-span-3 order-5 flex justify-around">
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" wire:click="clearSingle('{{Str::replace('00','15',$time)}}')">Clear</button>
                                <label class="relative flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.debounce.500ms="task.{{Str::replace('00','15',$time)}}.fifteen" class="vis-hidden">
                                    <span class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 py-1.5 border border-gray-400 rounded shadow">Fifteen</span>
                                </label>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" onclick="copyFromAbove({{ $loop->iteration + 0.5 }}, {{ $loop->iteration + 0.5 }})">Paste<span>&#8595</span></button>
                            </div>
                            <input id="task-{{ $loop->iteration + 0.5 }}" class="{{ $taskForCopying == Str::replace('00','15',$time) ?'bg-yellow-200':'' }} col-span-1 p-1 order-2" type="text" wire:model.debounce.500ms="task.{{Str::replace('00','15',$time)}}.task" wire:click="taskToPasteTo('{{Str::replace('00','15',$time)}}')" placeholder="Task #">
                            <input id="desc-{{ $loop->iteration + 0.5 }}" class="col-span-3 p-1 order-4" wire:model.debounce.500ms="task.{{Str::replace('00','15',$time)}}.work" placeholder="Work Completed"></input>
                        </div>
                    </div>
                @elseif(str_contains($time, '30'))
                    <div id="iteration-{{ $loop->iteration }}" x-data="{ open_{{Str::replace('-', '_', Str::slug($time))}}: '{{ !empty($task[Str::replace('30','45',$time)]['task']) }}'}" x-on:clear.window="open_{{Str::replace('-', '_', Str::slug($time))}} = false" class="space-y-2">
                        <div class="bg-blue-400 grid grid-cols-3 gap-y-2 p-2 rounded">
                            <h2  @click="open_{{Str::replace('-', '_', Str::slug($time))}}=!open_{{Str::replace('-', '_', Str::slug($time))}}; $wire.setFifteenMinutes('{{$time}}','30','45')" class="cursor-pointer col-span-1 p-1 text-center order-1 text-lg">{{ $time }}</h2>
                            <label class="col-span-1 p-1 order-3 text-center">
                                Logged
                                <input type="checkbox" wire:model.debounce.500ms="task.{{$time}}.completed">
                            </label>
                            <div class="col-span-3 order-5 flex justify-around">
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" wire:click="clearSingle('{{$time}}')">Clear</button>
                                <label @click="{{ empty($task[$time]['fifteen'])}} ? open_{{Str::replace('-', '_', Str::slug($time))}} = true : 'open_{{Str::replace('-', '_', Str::slug($time))}}=open_{{Str::replace('-', '_', Str::slug($time))}}'" wire:click="setFifteenMinutes('{{$time}}','30','45')"  "class="relative flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.debounce.500ms="task.{{$time}}.fifteen" class="vis-hidden">
                                    <span class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 py-1.5 border border-gray-400 rounded shadow">Fifteen</span>
                                </label>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" onclick="copyFromAbove({{ $loop->iteration }}, {{ $loop->iteration }})">Paste<span>&#8595</span></button>
                            </div>
                            <input id="task-{{ $loop->iteration }}" class="{{ $taskForCopying == $time ?'bg-yellow-200':'' }} col-span-1 p-1 order-2" type="text" wire:model.debounce.500ms="task.{{$time}}.task" wire:click="taskToPasteTo('{{$time}}')" placeholder="Task #">
                            <input id="desc-{{ $loop->iteration }}" class="col-span-3 p-1 order-4" wire:model.debounce.500ms="task.{{$time}}.work" placeholder="Work Completed"></input>
                        </div>
                        <div :class="open_{{Str::replace('-', '_', Str::slug($time))}} ? '' : 'hidden'" class="bg-green-400 grid grid-cols-3 gap-y-2 p-2 rounded">
                            <h2 @click="open_{{Str::replace('-', '_', Str::slug($time))}}=!open_{{Str::replace('-', '_', Str::slug($time))}}" class="cursor-pointer col-span-1 p-1 text-center order-1 text-lg">{{ Str::replace('30','45',$time) }}</h2>
                            <label class="col-span-1 p-1 order-3 text-center">
                                Logged
                                <input type="checkbox" wire:model.debounce.500ms="task.{{Str::replace('30','45',$time)}}.completed">
                            </label>
                            <div class="col-span-3 order-5 flex justify-around">
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow " wire:click="clearSingle('{{Str::replace('30','45',$time)}}')">Clear</button>
                                <label class="relative flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.debounce.500ms="task.{{Str::replace('30','45',$time)}}.fifteen" class="vis-hidden">
                                    <span class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 py-1.5 border border-gray-400 rounded shadow">Fifteen</span>
                                </label>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" onclick="copyFromAbove({{ $loop->iteration + 0.5 }}, {{ $loop->iteration + 0.5 }})">Paste<span>&#8595</span></button>
                            </div>
                            <input id="task-{{ $loop->iteration + 0.5 }}" class="{{ $taskForCopying == Str::replace('30','45',$time) ?'bg-yellow-200':'' }} col-span-1 p-1 order-2" type="text" wire:model.debounce.500ms="task.{{Str::replace('30','45',$time)}}.task" wire:click="taskToPasteTo('{{Str::replace('30','45',$time)}}')" placeholder="Task #">
                            <input id="desc-{{ $loop->iteration + 0.5 }}" class="col-span-3 p-1 order-4" wire:model.debounce.500ms="task.{{Str::replace('30','45',$time)}}.work" placeholder="Work Completed"></input>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </form>
<script>
function copyFromAbove(id, currentID) {
    if (id == 1) {
        alert('Nothing to Copy');
        return;
    }
    if (id % 1 == 0) {
        id = getCopyIdFromThirtyMinuteInput(id);
        tracker = 'thirty'
    } else {
        id = getCopyIdFromFifteenMinuteInput(id);
        tracker = 'fifteen'
    }

    var copyTask = document.getElementById("task-"+id);
    var copyDesc = document.getElementById("desc-"+id);
    if (copyTask.value == "" && copyDesc.value == "") {
        if  (tracker = 'thirty') {
            return copyFromAbove(id, currentID);
        }
        return copyFromAbove(id-1, currentID);
    }

    // Assign Task
    copyTask.select();
    copyTask.setSelectionRange(0, 99999); // For mobile devices
    var retVal = document.execCommand("copy");
    var currentTask = document.getElementById("task-"+currentID);
    currentTask.value = copyTask.value;

    // Assign Desc
    copyDesc.select();
    copyDesc.setSelectionRange(0, 99999); // For mobile devices
    var retVal = document.execCommand("copy");
    var currentDesc = document.getElementById("desc-"+currentID);
    currentDesc.value = copyDesc.value;

    // Call an event to get Livewire to send info to backend
    currentTask.dispatchEvent(new Event('input'));
    currentDesc.dispatchEvent(new Event('input'));

    //Focus on Current Task
    currentDesc.select();
    currentDesc.setSelectionRange(0, 99999);
}
function getCopyIdFromThirtyMinuteInput(id) {
    var currentIteration = document.getElementById("iteration-"+id);
    if (!currentIteration.previousElementSibling.getElementsByTagName('div')[2].classList.contains("hidden")) {
        return id - 0.5;
    }

    return id - 1;
}
function getCopyIdFromFifteenMinuteInput(id) {
   return id - 0.5;
}
</script>