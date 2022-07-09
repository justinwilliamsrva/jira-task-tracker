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
            $newArray[$tasks['task']][] = ['time'=>$key,'work'=> $tasks['work']];
        }

        return $newArray;
    }

}
