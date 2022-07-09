<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Output extends Component
{
    // public $tasks = [];

    public function render()
    {
        return view('livewire.output')->with('tasks', $this->tasks());
    }

    public function tasks()
    {
        return session()->has('tasks') ? $this->createNewArray(session('tasks')) : [];
    }

    public function createNewArray($sessiontasks)
    {
        $newArray['completed'] = [];
        $newArray['incomplete'] = [];

        foreach ($sessiontasks as $key => $tasks) {

            if (!empty($tasks['completed'])) {
                if (array_key_exists($tasks['task'], $newArray['completed'])) {
                    $newArray['completed'][$tasks['task']]['stats']++;
                } else {
                    $newArray['completed'][$tasks['task']]['stats'] = 1;
                }

                $newArray['completed'][$tasks['task']]['tasks'][] = ['time' => $key, 'work' => $tasks['work']];
            } else {
                if (array_key_exists($tasks['task'], $newArray['incomplete'])) {
                    $newArray['incomplete'][$tasks['task']]['stats']++;
                } else {
                    $newArray['incomplete'][$tasks['task']]['stats'] = 1;
                }

                $newArray['incomplete'][$tasks['task']]['tasks'][] = ['time' => $key, 'work' => $tasks['work']];
            }
        }
            return $newArray;

    }

    public function formatTimeBy30($number)
    {
        $number = $number / 2;

        $whole  = floor($number);
        $fraction  = (($number - $whole) * 60);

        return $whole . 'h ' . $fraction . 'm';
    }
}
