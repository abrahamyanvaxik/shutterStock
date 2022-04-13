<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;

class ShowTaskComponent extends Component
{
    use HasLivewireAuth;

    /** @var \App\Models\Task */
    public Task $task;

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('tasks.edit')
            ->extends('layouts.app');
    }
}
