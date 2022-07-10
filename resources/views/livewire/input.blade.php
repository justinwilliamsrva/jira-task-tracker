    <form class="flex flex-col space-y-6 py-2">
        <h1 class="text-center text-2xl">Add Time</h1>
        <div class="flex justify-center space-x-1 max-w-4xl mx-auto">
            <div class="flex flex-col w-32 sm:w-3/4">
                <label class="text-center">Start Time</label>
                <input type="number" class="border-2" wire:model="time.start">
            </div>
            <div class="flex flex-col w-32 sm:w-3/4">
                <label class="text-center">End Time</label>
                <input type="number" class="border-2" wire:model="time.end">
            </div>
        </div>
        <div class="flex justify-center space-x-3 mx-auto">
            <button wire:click="clear" class="py-2 px-8 bg-blue-200">Clear</button>
        </div>
        <div class="space-y-2">
            @foreach($timeArray as $time)
                <div class="bg-blue-400 grid grid-cols-3 sm:grid-cols-4 gap-y-1 sm:gap-x-1 p-2 rounded">
                    <h2 class="col-span-1 p-1 text-center sm:text-left order-1 text-lg">{{ $time }}</h2>
                    <label class="col-span-1 sm:col-span-3 p-1 order-3 sm:order-2 text-center sm:text-left">
                        Logged
                        <input type="checkbox" wire:model="task.{{$time}}.completed">
                    </label>
                    <input class="col-span-1 p-1 order-2 sm:order-3 " type="text" wire:model="task.{{$time}}.task" placeholder="Task #" >
                    <input  class="col-span-3 p-1 order-4" wire:model="task.{{$time}}.work" placeholder="Work Completed"></input>
                </div>
            @endforeach
        </div>
    </form>
