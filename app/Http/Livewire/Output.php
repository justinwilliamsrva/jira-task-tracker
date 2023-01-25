<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Output extends Component
{
    public $link;
    public $placeholder;
    public $totalTime;
    public $tasks;
    public $realLink;

    public function render()
    {
        return view('livewire.output');
    }
    public function mount()
    {
        $this->placeholder = (!empty(session('link')) || session()->has('link'))  ? ' Link is Saved in Session' : ((config('services.jira_link')) ? ' Link is Saved in ENV' : ' Add your Jira task link ');
        $this->tasks = $this->tasks();
        $this->realLink = (session('link') ?? config('services.jira_link') ?? '');
    }

    public function tasks()
    {
        return session()->has('tasks') ? $this->createNewArray(session('tasks')) : [];
    }
    public function createNewArray($sessiontasks)
    {
        $newArray['completed'] = [];
        $newArray['incomplete'] = [];
        $this->totalTime = 0;
        foreach ($sessiontasks as $key => $tasks) {
            if (empty($tasks['task']) && empty($tasks['work'])) {
                continue;
            }

            if (!empty($tasks['completed'])) {
                if (isset($tasks['work']) && substr($tasks['work'], 0, 3) == '15:') {
                    if (array_key_exists($tasks['task'], $newArray['completed'])) {
                        $newArray['completed'][$tasks['task']]['stats'] += .5;
                    } else {
                        $newArray['completed'][$tasks['task']]['stats'] = .5;
                    }
                    $this->totalTime += .5;
                } else {
                    if (array_key_exists($tasks['task'], $newArray['completed'])) {
                        $newArray['completed'][$tasks['task']]['stats']++;
                    } else {
                        $newArray['completed'][$tasks['task']]['stats'] = 1;
                    }
                    $this->totalTime += 1;
                }

                $newArray['completed'][$tasks['task']]['tasks'][] = ['time' => $key, 'work' => $tasks['work'] ?? ''];
            } else {
                if (isset($tasks['work']) && substr($tasks['work'], 0, 3) == '15:') {
                    if (array_key_exists($tasks['task'], $newArray['incomplete'])) {
                        $newArray['incomplete'][$tasks['task']]['stats'] += .5;
                    } else {
                        $newArray['incomplete'][$tasks['task']]['stats'] = .5;
                    }
                    $this->totalTime += .5;
                } else {
                    if (array_key_exists($tasks['task'], $newArray['incomplete'])) {
                        $newArray['incomplete'][$tasks['task']]['stats']++;
                    } else {
                        $newArray['incomplete'][$tasks['task']]['stats'] = 1;
                    }
                    $this->totalTime += 1;
                }

                $newArray['incomplete'][$tasks['task']]['tasks'][] = ['time' => $key, 'work' => $tasks['work'] ?? ''];
            }
        }

        // Sort Tasks by Time
        foreach ($newArray['incomplete'] as &$tasks) {
            array_multisort(array_map('strtotime', array_column($tasks['tasks'], 'time')), SORT_ASC, $tasks['tasks']);
        }
        foreach ($newArray['completed'] as &$tasks) {
            array_multisort(array_map('strtotime', array_column($tasks['tasks'], 'time')), SORT_ASC, $tasks['tasks']);
        }

        $this->totalTime = $this->formatTimeBy30($this->totalTime);

        return $newArray;
    }

    public function formatTimeBy30($number)
    {
        $number = $number / 2;

        $whole  = floor($number);
        $fraction  = (($number - $whole) * 60);

        if ($whole == 0) {
            return $fraction . 'm';
        } elseif ($fraction == 0) {
            return $whole . 'h';
        } else {
            return $whole . 'h ' . $fraction . 'm';
        }
    }

    public function save()
    {
        session(['link' => $this->link]);
        $this->realLink = session('link');
        $this->link = '';
        $this->placeholder = ' Link is Saved in Session';
    }

    public function logTask($key){
        $tasks = session('tasks');

        $filteredTasks = array_filter($tasks, function($task) use($key) {
            return (isset($task['completed']) ? $task['completed'] != true : true) && (isset($task['task']) ? $task['task'] == $key : false);
        });

        $loggedTasks = array_map(function ($task) {
            return [
                'task' => $task['task'],
                'work' => $task['work'],
                'completed' => true,
            ];
        }, $filteredTasks);
        session(['tasks' => array_replace($tasks, $loggedTasks)]);

        $this->tasks = $this->tasks();
    }
}
