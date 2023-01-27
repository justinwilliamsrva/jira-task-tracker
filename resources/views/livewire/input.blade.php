    <div class="flex flex-col space-y-6 py-2">
        <h1 class="text-center text-2xl">Add Time</h1>
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
                <p class="text-center text-6xl">{{ $this->showStart()}}<span class="text-base">{{ $counter['start'] < 12 ? 'am' : 'pm'}}</span></p>
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
        <div x-data class="flex justify-center space-x-3 mx-auto">
            <button onclick="confirm('Are you sure you want to clear your time?') || event.stopImmediatePropagation()" wire:click="clear()" class="py-2 px-8 bg-blue-200">Clear</button>
        </div>
        <div class="space-y-2">
            @foreach($timeArray as $time)
                @if(str_contains($time, '00'))
                    <div id="iteration-{{ $loop->iteration }}" x-data="{ open_{{Str::replace('-', '_', Str::slug($time))}}: '{{ !empty($task[Str::replace('00','15',$time)]['task']) }}'}" x-on:clear.window="open_{{Str::replace('-', '_', Str::slug($time))}} = false" class="space-y-2">
                        <div @dblclick="open_{{Str::replace('-', '_', Str::slug($time))}}=!open_{{Str::replace('-', '_', Str::slug($time))}}" class="bg-blue-400 grid grid-cols-3 sm:grid-cols-4 gap-y-2 sm:gap-x-1 p-2 rounded">
                            <h2 class="col-span-1 p-1 text-center sm:text-left order-1 text-lg">{{ $time }}</h2>
                            <label class="col-span-1 sm:col-span-1 p-1 order-3 sm:order-2 text-center sm:text-left">
                                Logged
                                <input type="checkbox" wire:model="task.{{$time}}.completed">
                            </label>
                            <div class="col-span-3 sm:col-span-2 order-5 sm:order-3 flex justify-around">
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" wire:click="clearSingle('{{$time}}')">Clear</button>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow opacity-50 cursor-not-allowed" disabled>Fifteen</button>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" onclick="copyFromAbove({{ $loop->iteration }})">Paste<span>&#8595</span></button>
                            </div>
                            <input id="task-{{ $loop->iteration }}" class="col-span-1 p-1 order-2 sm:order-4 " type="text" wire:model="task.{{$time}}.task" placeholder="Task #">
                            <input id="desc-{{ $loop->iteration }}" class="col-span-3 p-1 order-4 sm:order-5" wire:model="task.{{$time}}.work" placeholder="Work Completed"></input>
                        </div>
                        <div :class="open_{{Str::replace('-', '_', Str::slug($time))}} ? '' : 'hidden'" class="bg-green-400 grid grid-cols-3 sm:grid-cols-4 gap-y-2 sm:gap-x-1 p-2 rounded">
                            <h2 class="col-span-1 p-1 text-center sm:text-left order-1 text-lg">{{ str_replace('00','15',$time) }}</h2>
                            <label class="col-span-1 sm:col-span-1 p-1 order-3 sm:order-2 text-center sm:text-left">
                                Logged
                                <input type="checkbox" wire:model="task.{{str_replace('00','15',$time)}}.completed">
                            </label>
                            <div class="col-span-3 sm:col-span-2 order-5 sm:order-3 flex justify-around">
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" wire:click="clearSingle('{{Str::replace('00','15',$time)}}')">Clear</button>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow opacity-50 cursor-not-allowed" disabled>Fifteen</button>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" onclick="copyFromAbove({{ $loop->iteration + 0.5 }})">Paste<span>&#8595</span></button>
                            </div>
                            <input id="task-{{ $loop->iteration + 0.5 }}" class="col-span-1 p-1 order-2 sm:order-4 " type="text" wire:model="task.{{str_replace('00','15',$time)}}.task" placeholder="Task #">
                            <input id="desc-{{ $loop->iteration + 0.5 }}" class="col-span-3 p-1 order-4 sm:order-5" wire:model="task.{{str_replace('00','15',$time)}}.work" placeholder="Work Completed"></input>
                        </div>
                    </div>
                @elseif(str_contains($time, '30'))
                    <div id="iteration-{{ $loop->iteration }}" x-data="{ open_{{Str::replace('-', '_', Str::slug($time))}}: '{{ !empty($task[Str::replace('30','45',$time)]['task']) }}'}" x-on:clear.window="open_{{Str::replace('-', '_', Str::slug($time))}} = false" class="space-y-2">
                        <div @dblclick="open_{{Str::replace('-', '_', Str::slug($time))}}=!open_{{Str::replace('-', '_', Str::slug($time))}}" class="bg-blue-400 grid grid-cols-3 sm:grid-cols-4 gap-y-2 sm:gap-x-1 p-2 rounded">
                            <h2 class="col-span-1 p-1 text-center sm:text-left order-1 text-lg">{{ $time }}</h2>
                            <label class="col-span-1 sm:col-span-1 p-1 order-3 sm:order-2 text-center sm:text-left">
                                Logged
                                <input type="checkbox" wire:model="task.{{$time}}.completed">
                            </label>
                            <div class="col-span-3 sm:col-span-2 order-5 sm:order-3 flex justify-around">
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" wire:click="clearSingle('{{$time}}')">Clear</button>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow opacity-50 cursor-not-allowed" disabled>Fifteen</button>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" onclick="copyFromAbove({{ $loop->iteration }})">Paste<span>&#8595</span></button>
                            </div>
                            <input id="task-{{ $loop->iteration }}" class="col-span-1 p-1 order-2 sm:order-4 " type="text" wire:model="task.{{$time}}.task" placeholder="Task #">
                            <input id="desc-{{ $loop->iteration }}" class="col-span-3 p-1 order-4 sm:order-5" wire:model="task.{{$time}}.work" placeholder="Work Completed"></input>
                        </div>
                        <div :class="open_{{Str::replace('-', '_', Str::slug($time))}} ? '' : 'hidden'" class="bg-green-400 grid grid-cols-3 sm:grid-cols-4 gap-y-2 sm:gap-x-1 p-2 rounded">
                            <h2 class="col-span-1 p-1 text-center sm:text-left order-1 text-lg">{{ str_replace('30','45',$time) }}</h2>
                            <label class="col-span-1 sm:col-span-1 p-1 order-3 sm:order-2 text-center sm:text-left">
                                Logged
                                <input type="checkbox" wire:model="task.{{str_replace('30','45',$time)}}.completed">
                            </label>
                            <div class="col-span-3 sm:col-span-2 order-5 sm:order-3 flex justify-around">
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow " wire:click="clearSingle('{{Str::replace('30','45',$time)}}')">Clear</button>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow opacity-50 cursor-not-allowed" disabled>Fifteen</button>
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border border-gray-400 rounded shadow" onclick="copyFromAbove({{ $loop->iteration + 0.5 }})">Paste<span>&#8595</span></button>
                            </div>
                            <input id="task-{{ $loop->iteration + 0.5 }}" class="col-span-1 p-1 order-2 sm:order-4 " type="text" wire:model="task.{{str_replace('30','45',$time)}}.task" placeholder="Task #">
                            <input id="desc-{{ $loop->iteration + 0.5 }}" class="col-span-3 p-1 order-4 sm:order-5" wire:model="task.{{str_replace('30','45',$time)}}.work" placeholder="Work Completed"></input>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </form>
<script>
function copyFromAbove(id) {
    if (id == 1) {
        alert('Nothing to Copy');
        return;
    }

    if (id % 1 == 0) {
        copyId = getCopyIdFromThirtyMinuteInput(id);
    } else {
        copyId = getCopyIdFromFifteenMinuteInput(id);
    }

    var copyTask = document.getElementById("task-"+copyId);
    var copyDesc = document.getElementById("desc-"+copyId);
    if (copyTask.value == "" && copyDesc.value == "") {
        alert('Nothing to Copy');
        return;
    }

    // Assign Task
    copyTask.select();
    copyTask.setSelectionRange(0, 99999); // For mobile devices
    var retVal = document.execCommand("copy");
    var currentTask = document.getElementById("task-"+id);
    currentTask.value = copyTask.value;

    // Assign Desc
    copyDesc.select();
    copyDesc.setSelectionRange(0, 99999); // For mobile devices
    var retVal = document.execCommand("copy");
    var currentDesc = document.getElementById("desc-"+id);
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
    let copyId
    if (!currentIteration.previousElementSibling.getElementsByTagName('div')[2].classList.contains("hidden")) {
        return id - 0.5;
    }

    return id - 1;
}
function getCopyIdFromFifteenMinuteInput(id) {
   return id - 0.5;
}
</script>