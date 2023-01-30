<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Input extends Component
{
    public array $timeArray = [];
    public $counter = [];
    public $time = [];
    public $task = [];
    public $taskTable = [];
    public $taskTitles = [];
    public $taskForCopying = '';
    public function render()
    {
        return view('livewire.input');
    }

    public function mount()
    {
        $this->task = session('tasks') ?? [];
        $this->counter = session('counter') ?? ['start' => ltrim(date('H'), "0"), 'end' => 6];
        $this->taskTable = session('taskTable') ?? [];
        $this->taskTitles = session('taskTitles') ?? [];
        $this->timeChanger();
    }

    public function save()
    {
        session(['tasks' => $this->task, 'counter' => $this->counter, 'taskTable' => $this->taskTable, 'taskTitles' => $this->taskTitles]);
    }

    public function clear($scope = null)
    {
        session()->forget('tasks');
        session()->forget('counter');
        $this->taskTable = array_filter($this->taskTable, function($task) {return $task['locked'];});
        session(['taskTitles' => $this->taskTitles]);
        if ($scope == 'All') {
            session()->forget('taskTable');
            session()->forget('taskTitles');
            $this->taskTable = [];
            $this->taskTitles = [];
        }
        session()->save();
        $this->task = [];
        $this->counter = ['start' => ltrim(date('H'), "0"), 'end' => 6];
        $this->timeChanger();
        $this->dispatchBrowserEvent('clear');
    }

    public function updatedTask($value, $key)
    {
        if (substr($key, -4) == 'work' && !empty($value)) {
            $time = str_replace('.work', '', $key);
            $taskName = $this->task[$time]['task'];
            $this->taskTable[$taskName]['locked'] = true;
        }
        $this->refreshTaskList();
        $this->save();
    }
    public function updatedTaskTable()
    {
        $this->save();
    }
    public function updatedTaskTitles()
    {
        $this->refreshTaskList();
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
        Arr::forget($this->task, $time);
        $this->refreshTaskList();
        $this->save();

    }

    public function setFifteenMinutes($time, $mainNum, $replaceNum)
    {
        if (!isset($this->task[Str::replace($mainNum, $replaceNum, $time)]['fifteen'])) {
            $this->task[Str::replace($mainNum, $replaceNum, $time)]['fifteen'] = true;
            session()->save();
        }
    }

    public function lockAll()
    {
        $this->taskTable = Arr::map($this->taskTable , function ($value) {
            $value['locked'] = true;
            return $value;
        });
    }

    public function copyPasteTask($taskName)
    {
        $this->task[$this->taskForCopying]['task'] = $taskName;
        $this->save();
    }

    public function taskToPasteTo($time)
    {
        $this->taskForCopying = $time;
    }

    protected function refreshTaskList()
    {
        $this->taskTable = array_filter($this->taskTable, function($task) {return $task['locked'];});
        foreach($this->task as $task) {
            if (!empty($task['task'])) {
                $this->taskTable[$task['task']]['locked'] = $this->taskTable[$task['task']]['locked'] ?? false;
                $this->taskTitles[$task['task']] = $this->taskTitles[$task['task']] ?? '';
            }
        }
    }

    public function getCurrentTime() {
        $this->counter['start'] = ltrim(date('H'), "0");
        $this->save();
        $this->timeChanger();
    }
}
