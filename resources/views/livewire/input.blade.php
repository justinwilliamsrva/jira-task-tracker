<div>
    <form class="flex flex-col space-y-6 py-2" wire:submit.prevent="search">
        <h1 class="text-center text-2xl">Input</h1>
        <div class="flex justify-center space-x-3 mx-auto">
            <button wire:click="clear" class="py-2 px-8 bg-blue-200">Clear</button>
            <button type="submit" class="py-2 px-8 bg-green-200">Save</button>
        </div>
        <div class="space-y-2">
            @foreach($timeArray as $time)
                <div class="flex justify-around bg-blue-500 p-2">
                    <h2>{{ $time }}</h2>
                    <input type="hidden" wire:model="time[]" value="{{ $time }}">
                    {{--<input type="text" wire:model="task[]" placeholder="Task">
                    <textarea cols="30" wire:model="completed[]" placeholder="Completed Work"></textarea>--}}
                </div>
            @endforeach
        </div>
    </form>
</div>