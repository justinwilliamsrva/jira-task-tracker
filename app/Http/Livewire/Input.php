<?php

namespace App\Http\Livewire;

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
        $this->counter = session('counter') ?? ['start'=> 8,'end'=> 5];
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
        $this->counter =  ['start'=>8,'end'=> 5];
        $this->timeChanger();


    }

    public function updatedTask()
    {
        $this->save();
    }

    public function timeChanger()
    {
        $start = $this->counter['start'] ;
        $end = $this->counter['end']+12;
        $timeArray = [];
        $newTimeArray = [];
        //Get every number from start to finish
        do{
            if($start > 12){
                $insert =$start - 12;
            }else{
                $insert =$start;
            }
            array_push($timeArray, $insert);
            $start++;
        }while ($start <= $end);

        //
        foreach ($timeArray as $time)
        {
            array_push($newTimeArray, $time.':00', $time.':30');
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


}
