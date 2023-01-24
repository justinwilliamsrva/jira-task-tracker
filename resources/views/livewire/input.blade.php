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
                    <div x-data="{ open: '{{ !empty($task[str_replace('00','15',$time)]['task']) }}' }" class="space-y-2">
                        <div @dblclick="open=!open" class="bg-blue-400 grid grid-cols-3 sm:grid-cols-4 gap-y-1 sm:gap-x-1 p-2 rounded">
                            <h2 class="col-span-1 p-1 text-center sm:text-left order-1 text-lg">{{ $time }}</h2>
                            <label class="col-span-1 sm:col-span-3 p-1 order-3 sm:order-2 text-center sm:text-left">
                                Logged
                                <input type="checkbox" wire:model="task.{{$time}}.completed">
                            </label>
                            <input class="col-span-1 p-1 order-2 sm:order-3 " type="text" wire:model="task.{{$time}}.task" placeholder="Task #">
                            <input class="col-span-3 p-1 order-4" wire:model="task.{{$time}}.work" placeholder="Work Completed"></input>
                        </div>
                        <div x-show="open" class="bg-green-400 grid grid-cols-3 sm:grid-cols-4 gap-y-1 sm:gap-x-1 p-2 rounded">
                            <h2 class="col-span-1 p-1 text-center sm:text-left order-1 text-lg">{{ str_replace('00','15',$time) }}</h2>
                            <label class="col-span-1 sm:col-span-3 p-1 order-3 sm:order-2 text-center sm:text-left">
                                Logged
                                <input type="checkbox" wire:model="task.{{str_replace('00','15',$time)}}.completed">
                            </label>
                            <input class="col-span-1 p-1 order-2 sm:order-3 " type="text" wire:model="task.{{str_replace('00','15',$time)}}.task" placeholder="Task #">
                            <input class="col-span-3 p-1 order-4" wire:model="task.{{str_replace('00','15',$time)}}.work" placeholder="Work Completed"></input>
                        </div>
                    </div>
                @elseif(str_contains($time, '30'))
                    <div x-data="{ open: '{{ !empty($task[str_replace('30','45',$time)]['task']) }}' }" class="space-y-2">
                        <div @dblclick="open=!open" class="bg-blue-400 grid grid-cols-3 sm:grid-cols-4 gap-y-1 sm:gap-x-1 p-2 rounded">
                            <h2 class="col-span-1 p-1 text-center sm:text-left order-1 text-lg">{{ $time }}</h2>
                            <label class="col-span-1 sm:col-span-3 p-1 order-3 sm:order-2 text-center sm:text-left">
                                Logged
                                <input type="checkbox" wire:model="task.{{$time}}.completed">
                            </label>
                            <input class="col-span-1 p-1 order-2 sm:order-3 " type="text" wire:model="task.{{$time}}.task" placeholder="Task #">
                            <input class="col-span-3 p-1 order-4" wire:model="task.{{$time}}.work" placeholder="Work Completed"></input>
                        </div>
                        <div x-show="open" class="bg-green-400 grid grid-cols-3 sm:grid-cols-4 gap-y-1 sm:gap-x-1 p-2 rounded">
                            <h2 class="col-span-1 p-1 text-center sm:text-left order-1 text-lg">{{ str_replace('30','45',$time) }}</h2>
                            <label class="col-span-1 sm:col-span-3 p-1 order-3 sm:order-2 text-center sm:text-left">
                                Logged
                                <input type="checkbox" wire:model="task.{{str_replace('30','45',$time) }}.completed">
                            </label>
                            <input class="col-span-1 p-1 order-2 sm:order-3 " type="text" wire:model="task.{{str_replace('30','45',$time) }}.task" placeholder="Task #">
                            <input class="col-span-3 p-1 order-4" wire:model="task.{{str_replace('30','45',$time) }}.work" placeholder="Work Completed"></input>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </form>