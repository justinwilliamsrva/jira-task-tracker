<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Services\JiraService;
use Illuminate\Support\Str;
use Livewire\Component;

class Input extends Component
{
    public array $timeArray = [];

    public $counter = [];

    public $time = [];

    public $task = [];

    public $taskTable = [];

    public $taskTitles = [];

    public $taskForCopying = '';

    public $realLink = '';

    protected $jiraService;

    public $expandedRows = [];

    public $inputId = '';


    public function render()
    {
        return view('livewire.input');
    }
    public function boot()
    {
        $this->jiraService = new JiraService();
    }

    public function mount()
    {
        $this->task = session('tasks') ?? [];
        $this->counter = session('counter') ?? ['start' => (date('H') == 00 ? date('H') : ltrim(date('H'), '0')), 'end' => (ltrim(date('H'), '0') > 14) ? 12 : 6];
        $this->taskTable = session('taskTable') ?? [];
        $this->taskTitles = session('taskTitles') ?? [];
        $this->timeChanger();
        $this->realLink = (session('link') ?? config('services.jira_link') ?? '');
    }

    public function save()
    {
        unset($this->task['']);
        session(['tasks' => $this->task, 'counter' => $this->counter, 'taskTable' => $this->taskTable, 'taskTitles' => $this->taskTitles]);
    }

    public function clear($scope = null)
    {
        session()->forget('tasks');
        session()->forget('counter');
        $this->taskTable = array_filter($this->taskTable, function ($task) {
            return $task['locked'];
        });
        session(['taskTable' => $this->taskTable]);
        if ($scope == 'All') {
            session()->forget('taskTable');
            session()->forget('taskTitles');
            $this->taskTable = [];
            $this->taskTitles = [];
        }
        session()->save();
        $this->task = [];
        $this->counter = ['start' => ltrim(date('H'), '0'), 'end' => (ltrim(date('H'), '0') > 14) ? 12 : 6];
        $this->taskForCopying = '';
        $this->timeChanger();
        $this->dispatchBrowserEvent('clear');
        $this->expandedRows = [];
    }

    public function updatedTask($value, $key)
    {
        if (substr($key, -4) == 'work' && ! empty($value)) {
            $time = str_replace('.work', '', $key);
            $taskName = $this->task[$time]['task'];
            $this->taskTable[$taskName]['locked'] = true;
        }
        $this->refreshTaskList();
        $this->save();
        $this->expandedRows = []; // Clear the array
    }

    public function updatedTaskTable()
    {
        $this->save();
        $this->expandedRows = []; // Clear the array
    }

    public function updatedTaskTitles()
    {
        $this->refreshTaskList();
        $this->save();
        $this->expandedRows = []; // Clear the array
    }

    public function timeChanger()
    {
        $start = $this->counter['start'];
        $end = $this->counter['end'] + 11;
        $newTimeArray = [];

        $startDate = Carbon::parse('2021-01-01 '.$start.':00:00');
        $period = $startDate->toPeriod('2021-01-01 '.$end.':30:00', 30, 'minutes')->toArray();
        foreach ($period as $date) {
            array_push($newTimeArray, Carbon::parse($date->toDateTimeString())->format('g:i A'));
        }
        $this->timeArray = $newTimeArray;
        $this->expandedRows = []; // Clear the array
    }

    public function increment($value)
    {
        $this->counter[$value] += 1;
        $this->incrementEndTimeIfNeeded();
        $this->timeChanger();
        $this->save();
        $this->expandedRows = []; // Clear the array

    }

    public function decrement($value)
    {
        $this->counter[$value] -= 1;
        $this->incrementEndTimeIfNeeded();
        $this->timeChanger();
        $this->save();
        $this->expandedRows = []; // Clear the array
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
        $this->expandedRows = []; // Clear the array
    }

    public function setFifteenMinutes($time, $mainNum, $replaceNum)
    {
        if (! isset($this->task[Str::replace($mainNum, $replaceNum, $time)]['fifteen']) || ! $this->task[Str::replace($mainNum, $replaceNum, $time)]['fifteen']) {
            $this->task[Str::replace($mainNum, $replaceNum, $time)]['fifteen'] = true;
            $this->save();
        }
    }

    public function lockAll()
    {
        $this->taskTable = Arr::map($this->taskTable, function ($value) {
            $value['locked'] = true;

            return $value;
        });
    }

    public function copyPasteTask($taskName)
    {
        $this->toggleTasks($taskName);
        if (!empty($this->taskForCopying)) {
            $this->task[$this->taskForCopying]['task'] = $taskName;
        }
        $this->dispatchBrowserEvent('focus-on-desc-input', ['id' => $this->inputId]);
        $this->save();
    }

    public function taskToPasteTo($time, $inputId)
    {
        $this->taskForCopying = $time;
        $this->inputId = $inputId;
    }

    protected function refreshTaskList()
    {
        $this->taskTable = array_filter($this->taskTable, function ($task) {
            return $task['locked'];
        });

        foreach ($this->task as $task) {
            if (!empty($task['task'])) {
                $this->taskTable[$task['task']]['locked'] = $this->taskTable[$task['task']]['locked'] ?? false;
                $this->taskTitles[$task['task']] = $this->taskTitles[$task['task']] ?? ($this->jiraService->getTitle($task['task']) ?? '');
            }
        }
        $this->expandedRows = []; // Clear the array
    }

    public function getCurrentTime()
    {
        $this->counter['start'] = ltrim(date('H'), '0');
        $this->incrementEndTimeIfNeeded();
        $this->save();
        $this->timeChanger();
        $this->expandedRows = []; // Clear the array
    }

    public function copyFromAbove($time, $inputId) {
        $timeSorted = $this->task;

        //Sort the tasks by time.
        uksort($timeSorted, function($a, $b){
            return strtotime($a) - strtotime($b);
        });

        $previousTime = null;

        // Get most recent previous task to the one click on.
        foreach ($timeSorted as $key => $value) {
            if (strtotime($key) >= strtotime($time)) {
                break;
            }
            if (isset($value['work'])) {
                $previousTime = $key;
            }
        }
        // Assigned previous task to the task clicked on.
        if ($previousTime !== null) {
           $this->task[$time]['task'] = $timeSorted[$previousTime]['task'];
           $this->task[$time]['work'] = $timeSorted[$previousTime]['work'];
        }
        $this->save();

        // Set focus after copy.
        $this->dispatchBrowserEvent('focus-on-desc-input', ['id' => $inputId]);
        $this->expandedRows = []; // Clear the array
        $this->expandedRows[$this->task[$time]['task']] = true;
    }

    public function getTasksByTaskName($taskName) {
        $fullTaskArray = [];

        foreach($this->task as $task) {
            if (isset($task['task']) && $task['task'] == $taskName) {
                if (isset($task['work']) && !in_array($task['work'], $fullTaskArray)) {
                    $fullTaskArray[] = $task['work'] ?? '';
                  }
            }
        }

        return $fullTaskArray;
    }

    public function toggleTasks($taskName)
    {
        $value = $this->expandedRows[$taskName] ?? null; // Save the value for the key $taskName, if it exists
        $this->expandedRows = []; // Clear the array
        if ($value !== null) {
            $this->expandedRows[$taskName] = $value; // Restore the saved value
        }
        $this->expandedRows[$taskName] = isset($this->expandedRows[$taskName]) ? !$this->expandedRows[$taskName] : true;
    }

    public function copyWorkName($workName, $taskName) {
        $this->task[$this->taskForCopying]['work'] = $workName;
        $this->toggleTasks($taskName);
        $this->save();
    }

public function buttonColor() {

    $listOfColors = [
        'red', 'purple', 'blue', 'green', 'yellow', 'pink','cyan'
    ];

    $color = $listOfColors[array_rand($listOfColors)];

    return 'bg-'.$color.'-200 hover:bg-'.$color.'-500';
}

    public function incrementEndTimeIfNeeded(){
        if($this->counter['end'] <= ($this->counter['start'] - 12)) {
            $this->counter['end'] = $this->counter['start'] - 11;
        }
    }
}
