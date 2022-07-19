<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Output extends Component
{
    public $link;
    public $placeholder;

    public function render()
    {
        return view('livewire.output');
    }
    public function mount()
    {
        $this->placeholder = (!empty(session('link')) || session()->has('link'))  ? 'Link is Saved in Session' : 'Add your Jira task link ';
        $this->tasks = $this->tasks();
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

        foreach ($sessiontasks as $key => $tasks) {
            $tasks['task']  = $tasks['task'] ?? 'Add a task #';

            if (!empty($tasks['completed'])) {
                if (array_key_exists($tasks['task'], $newArray['completed'])) {
                    $newArray['completed'][$tasks['task']]['stats']++;
                } else {
                    $newArray['completed'][$tasks['task']]['stats'] = 1;
                }

                $newArray['completed'][$tasks['task']]['tasks'][] = ['time' => $key, 'work' => $tasks['work'] ?? ''];
            } else {
                if (array_key_exists($tasks['task'], $newArray['incomplete'])) {
                    $newArray['incomplete'][$tasks['task']]['stats']++;
                } else {
                    $newArray['incomplete'][$tasks['task']]['stats'] = 1;
                }

                $newArray['incomplete'][$tasks['task']]['tasks'][] = ['time' => $key, 'work' => $tasks['work'] ?? ''];
            }
        }
            return $newArray;

    }

    public function formatTimeBy30($number)
    {
        $number = $number / 2;

        $whole  = floor($number);
        $fraction  = (($number - $whole) * 60);

        if($whole == 0){
           return $fraction . 'm';
        } elseif ($fraction == 0 ) {
            return $whole . 'h';
        }else{
            return $whole . 'h ' . $fraction . 'm';
        }
    }

    public function save()
    {
        session(['link' => $this->link]);
        $this->realLink = session('link');
        $this->link ='';
        $this->placeholder = 'Link is Saved in Session';

    }
}
