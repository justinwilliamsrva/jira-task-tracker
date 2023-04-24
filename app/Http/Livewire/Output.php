<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\JiraService;

class Output extends Component
{
    public $link;

    public $placeholder;

    public $totalTime;

    public $tasks;

    public $realLink;

    public $taskFormat;

    public function render()
    {
        return view('livewire.output');
    }

    public function mount()
    {
        $this->placeholder = (! empty(session('link')) || session()->has('link')) ? ' Link is Saved in Session' : ((config('services.jira_link')) ? ' Link is Saved in ENV' : ' Add your Jira task link ');
        $this->taskFormat = session('taskFormat') ?? 'time';
        $this->tasks = $this->tasks();
        $this->realLink = (session('link') ?? config('services.jira_link') ?? '');
    }

    public function tasks()
    {
        if (! session()->has('tasks')) {
            return [];
        } elseif ($this->taskFormat == 'task') {
            return $this->createTaskFormat(session('tasks'));
        }

        return $this->createTimeFormat(session('tasks'));
    }

    public function createTimeFormat($sessionTasks)
    {
        $newArray['completed'] = [];
        $newArray['incomplete'] = [];
        $this->totalTime = 0;
        foreach ($sessionTasks as $key => $tasks) {
            if (empty($tasks['task']) && empty($tasks['work'])) {
                continue;
            }

            if (! empty($tasks['completed'])) {
                if (isset($tasks['work']) && ! empty($tasks['fifteen'])) {
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

                $newArray['completed'][$tasks['task']]['tasks'][] = ['time' => $key, 'work' => $tasks['work'] ?? '', 'fifteen' => $tasks['fifteen'] ?? false];
            } else {
                if (isset($tasks['work']) && ! empty($tasks['fifteen'])) {
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

                $newArray['incomplete'][$tasks['task']]['tasks'][] = ['time' => $key, 'work' => $tasks['work'] ?? '', 'fifteen' => $tasks['fifteen'] ?? false];
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

    public function createTaskFormat($sessionTasks)
    {
        $formattedSessionTasks['completed'] = [];
        $formattedSessionTasks['incomplete'] = [];
        $this->totalTime = 0;
        foreach ($sessionTasks as $task) {
            if (! empty($task['task']) && ! empty($task['work'])) {
                if (! empty($task['completed'])) {
                    if (! empty($task['fifteen'])) {
                        $this->totalTime += .5;
                        $formattedSessionTasks['completed'][$task['task']]['taskList'][$task['work']] = (isset($formattedSessionTasks['completed'][$task['task']]['taskList'][$task['work']])) ? $formattedSessionTasks['completed'][$task['task']]['taskList'][$task['work']] + 0.5 : 0.5;
                        $formattedSessionTasks['completed'][$task['task']]['stats'] = (isset($formattedSessionTasks['completed'][$task['task']]['stats'])) ? $formattedSessionTasks['completed'][$task['task']]['stats'] + 0.5 : 0.5;
                    } else {
                        $this->totalTime += 1;
                        $formattedSessionTasks['completed'][$task['task']]['taskList'][$task['work']] = (isset($formattedSessionTasks['completed'][$task['task']]['taskList'][$task['work']])) ? $formattedSessionTasks['completed'][$task['task']]['taskList'][$task['work']] + 1 : 1;
                        $formattedSessionTasks['completed'][$task['task']]['stats'] = (isset($formattedSessionTasks['completed'][$task['task']]['stats'])) ? $formattedSessionTasks['completed'][$task['task']]['stats'] + 1 : 1;
                    }
                } else {
                    if (! empty($task['fifteen'])) {
                        $this->totalTime += .5;
                        $formattedSessionTasks['incomplete'][$task['task']]['taskList'][$task['work']] = (isset($formattedSessionTasks['incomplete'][$task['task']]['taskList'][$task['work']])) ? $formattedSessionTasks['incomplete'][$task['task']]['taskList'][$task['work']] + 0.5 : 0.5;
                        $formattedSessionTasks['incomplete'][$task['task']]['stats'] = (isset($formattedSessionTasks['incomplete'][$task['task']]['stats'])) ? $formattedSessionTasks['incomplete'][$task['task']]['stats'] + 0.5 : 0.5;
                    } else {
                        $this->totalTime += 1;
                        $formattedSessionTasks['incomplete'][$task['task']]['taskList'][$task['work']] = (isset($formattedSessionTasks['incomplete'][$task['task']]['taskList'][$task['work']])) ? $formattedSessionTasks['incomplete'][$task['task']]['taskList'][$task['work']] + 1 : 1;
                        $formattedSessionTasks['incomplete'][$task['task']]['stats'] = (isset($formattedSessionTasks['incomplete'][$task['task']]['stats'])) ? $formattedSessionTasks['incomplete'][$task['task']]['stats'] + 1 : 1;
                    }
                }
            }
        }

        $this->totalTime = $this->formatTimeBy30($this->totalTime);

        return $formattedSessionTasks;
    }

    public static function formatTimeBy30($number)
    {
        $number = $number / 2;

        $whole = floor($number);
        $fraction = (($number - $whole) * 60);

        if ($whole == 0) {
            return $fraction.'m';
        } elseif ($fraction == 0) {
            return $whole.'h';
        } else {
            return $whole.'h '.$fraction.'m';
        }
    }

    public function save()
    {
        session(['link' => $this->link]);
        $this->realLink = session('link');
        $this->link = '';
        $this->placeholder = ' Link is Saved in Session';
    }

    public function logTask($key)
    {
        //Get incomplete Tasks for Jira Loggin
        $allTasks = $this->tasks();

        $messages = $this->taskFormat == 'task' ? $allTasks['incomplete'][$key]['taskList'] : $allTasks['incomplete'][$key]['tasks'];
        $timeSpent = $allTasks['incomplete'][$key]['stats'];

        // Log in this app
        $tasks = session('tasks');

        $filteredTasks = array_filter($tasks, function ($task) use ($key) {
            return (isset($task['completed']) ? $task['completed'] != true : true) && (isset($task['task']) ? $task['task'] == $key : false);
        });

        $loggedTasks = array_map(function ($task) {
            return [
                'task' => $task['task'],
                'work' => $task['work'] ?? '',
                'completed' => true,
                'fifteen' => $task['fifteen'] ?? false,
            ];
        }, $filteredTasks);
        session(['tasks' => array_replace($tasks, $loggedTasks)]);

        $this->tasks = $this->tasks();

        // Log in JIRA
        $timeStarted = $this->taskFormat == 'task' ? key($filteredTasks) : $this->tasks['completed'][$key]['tasks'][0]['time'];

        $jiraService = new JiraService();
        $result = $jiraService->logTask($key, $messages, $timeSpent, $timeStarted , $this->taskFormat);

        if ($result) {
            session()->flash('message', 'Task logged successfully!');
        } else {
            session()->flash('error', 'An error occurred while logging the task.');
        }
    }

    public function unLogTask($key)
    {
        $tasks = session('tasks');

        $filteredTasks = array_filter($tasks, function ($task) use ($key) {
            return (! empty($task['completed'])) && (isset($task['task']) ? $task['task'] == $key : false);
        });

        $loggedTasks = array_map(function ($task) {
            return [
                'task' => $task['task'],
                'work' => $task['work'] ?? '',
                'completed' => false,
                'fifteen' => $task['fifteen'] ?? false,
            ];
        }, $filteredTasks);
        session(['tasks' => array_replace($tasks, $loggedTasks)]);

        $this->tasks = $this->tasks();
    }

    public function formatTasks($format)
    {
        $this->taskFormat = $format;
        session(['taskFormat' => $format]);
        $this->tasks = $this->tasks();
    }
}
