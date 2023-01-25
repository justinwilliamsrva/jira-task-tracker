<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class Input extends Component
{
    public array $timeArray = [];
    public $counter = [];
    public $time = [];
    public $task = [];
    public function render()
    {
        return view('livewire.input');
    }

    public function mount()
    {
        $this->task = session('tasks') ?? [];
        $this->counter = session('counter') ?? ['start' => 8, 'end' => 6];
        $this->timeChanger();
    }

    public function save()
    {
        session(['tasks' => $this->task, 'counter' => $this->counter]);
    }

    public function clear()
    {
        session()->forget('tasks');
        session()->forget('counter');
        session()->save();
        $this->task = [];
        $this->counter =  ['start' => 8, 'end' => 6];
        $this->timeChanger();
    }

    public function updatedTask()
    {
        $this->save();
    }

    public function timeChanger()
    {
        $start = $this->counter['start'];
        $end = $this->counter['end'] + 11;
        $newTimeArray = [];

        $startDate = Carbon::parse('2021-01-01 ' . $start . ':00:00');
        $period = $startDate->toPeriod('2021-01-01 ' . $end . ':30:00', 30, 'minutes')->toArray();
        foreach ($period as $date) {
            array_push($newTimeArray, Carbon::parse($date->toDateTimeString())->format('g:i A'));
        }
        $this->timeArray = $newTimeArray;
    }

    public function increment($value)
    {
        $this->counter[$value] += 1;

        $this->timeChanger();
        $this->save();
    }
    public function decrement($value)
    {
        $this->counter[$value] -= 1;
        $this->timeChanger();
        $this->save();
    }

    public function showStart()
    {
        if ($this->counter['start'] == 0) {
            return 12;
        }
        if ($this->counter['start'] > 12) {
            return $this->counter['start'] - 12;
        }
        return $this->counter['start'];
    }

    public function clearSingle($time)
    {
        session()->forget("tasks.{$time}");
        $this->task = session('tasks') ?? [];
    }
}
