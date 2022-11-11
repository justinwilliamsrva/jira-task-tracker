<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Output extends Component
{
    public $link;
    public $placeholder;
    public $totaltime;

    public function render()
    {
        return view('livewire.output');
    }
    public function mount()
    {
        $this->placeholder = (!empty(session('link')) || session()->has('link'))  ? 'Link is Saved in Session' : 'Add your Jira task link ';
        $this->tasks = $this->tasks();
        $this->totaltime = $this->formatTimeBy30($this->totaltime);
        $this->realLink =  session('link') ?? '';
    }

    public function tasks()
    {
        return session()->has('tasks') ? $this->createNewArray(session('tasks')) : [];
    }

    public function createNewArray($sessiontasks)
    {
        $newArray['completed'] = [];
        $newArray['incomplete'] = [];
        $this->totaltime = 0;

        foreach ($sessiontasks as $key => $tasks) {
            if (empty($tasks['task']) && empty($tasks['work'])) {
                continue;
            }

            if (!empty($tasks['completed'])) {
                if (substr($tasks['work'], 0, 3) == '15:') {
                    if (array_key_exists($tasks['task'], $newArray['completed'])) {
                        $newArray['completed'][$tasks['task']]['stats'] += .5;
                    } else {
                        $newArray['completed'][$tasks['task']]['stats'] = .5;
                    }
                    $this->totaltime += .5;
                } else {
                    if (array_key_exists($tasks['task'], $newArray['completed'])) {
                        $newArray['completed'][$tasks['task']]['stats']++;
                    } else {
                        $newArray['completed'][$tasks['task']]['stats'] = 1;
                    }
                    $this->totaltime += 1;
                }

                $newArray['completed'][$tasks['task']]['tasks'][] = ['time' => $key, 'work' => $tasks['work'] ?? ''];
            } else {
                if (substr($tasks['work'], 0, 3) == '15:') {
                    if (array_key_exists($tasks['task'], $newArray['incomplete'])) {
                        $newArray['incomplete'][$tasks['task']]['stats'] += .5;
                    } else {
                        $newArray['incomplete'][$tasks['task']]['stats'] = .5;
                    }
                    $this->totaltime += .5;
                } else {
                    if (array_key_exists($tasks['task'], $newArray['incomplete'])) {
                        $newArray['incomplete'][$tasks['task']]['stats']++;
                    } else {
                        $newArray['incomplete'][$tasks['task']]['stats'] = 1;
                    }
                    $this->totaltime += 1;
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
        $this->placeholder = 'Link is Saved in Session';
    }
}
