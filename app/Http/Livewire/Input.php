<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Input extends Component
{
    public array $timeArray = [
        '8:30','9:00','9:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30',
    ];
    public $task = [];
    public function render()
    {
        return view('livewire.input');
    }

    public function mount()
    {
        $this->task = session('tasks');
    }

    public function save()
    {
        session(['tasks' => $this->task]);
    }

    public function clear()
    {
        session()->forget('tasks');
        session()->save();
        $this->task = [];
    }

    public function updatedTask()
    {
        $this->save();
    }


}
