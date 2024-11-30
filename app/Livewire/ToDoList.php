<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ToDo;

class ToDoList extends Component
{
    public $task;

    protected $listeners = ['taskAdded' => '$refresh'];

    public function render()
    {
        $data['todos'] = ToDo::all();
        return view('livewire.to-do-list', $data)->layout('layouts.app');
    }

    public function addTask()
    {
        ToDo::create(['task' => $this->task]);
        $this->task = '';
        $this->emit('taskAdded');
    }

    public function toggleCompletion($taskId)
    {
        $task = ToDo::find($taskId);
        $task->completed = !$task->completed;
        $task->save();
        $this->emit('taskUpdated');
    }
}
