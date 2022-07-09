<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Input extends Component
{
    public array $timeArray = [];
    public $time = [];
    public $task = [];
    public function render()
    {
        return view('livewire.input');
    }

    public function mount()
    {
        $this->task = session('tasks') ?? [];
        $this->time = session('time') ?? ['start'=>8,'end'=>17];
        $this->timeChanger();
    }

    public function save()
    {
        session(['tasks' => $this->task, 'time' => $this->time]);
    }

    public function clear()
    {
        session()->forget('tasks');
        session()->forget('time');
        session()->save();
        $this->task = [];
        $this->time = ['start'=>8,'end'=>17];
    }

    public function updatedTask()
    {
        $this->save();
    }

    public function updatedTime()
    {
        $this->save();

        $start = $this->time['start'];
        $end = $this->time['end'];
        $timeArray = [];
        $newTimeArray = [];
        //Get every number from start to finish
        do{
            array_push($timeArray, $start);
            $start++;
        }while ($start <= $end);

        //
        foreach ($timeArray as $time)
        {
            array_push($newTimeArray, $time.':00', $time.':30');
        }

        $this->timeArray = $newTimeArray;
    }

    public function timeChanger()
    {
        $start = $this->time['start'];
        $end = $this->time['end'];
        $timeArray = [];
        $newTimeArray = [];
        //Get every number from start to finish
        do{
            array_push($timeArray, $start);
            $start++;
        }while ($start <= $end);

        //
        foreach ($timeArray as $time)
        {
            array_push($newTimeArray, $time.':00', $time.':30');
        }

        $this->timeArray = $newTimeArray;
    }


}
