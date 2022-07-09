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
        $newArray = [];
        foreach($sessiontasks as $key => $tasks)
        {
            if (array_key_exists($tasks['task'], $newArray)) {
                $newArray[$tasks['task']]['stats']++ ;
            } else {
                $newArray[$tasks['task']]['stats'] = 1 ;
            }

            $newArray[$tasks['task']]['tasks'][] = ['time'=>$key,'work'=> $tasks['work']];
        }

        return $newArray;
    }

    public function formatTimeBy30($number)
    {
        $number = $number/2;

        $whole  = floor($number);
        $fraction  = (($number - $whole)*60);

        return $whole.'h '.$fraction.'m';
    }

}
