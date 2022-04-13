<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;

class CreateTaskComponent extends Component
{
    use HasLivewireAuth;

    /** @var \App\Models\Task */
    public $task;

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('tasks.create')->extends('layouts.app');
    }

    /**
     * Store new task.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate();

        // create-review
        Task::create([
            'title' => $this->task['title'],
            'description' => $this->task['description'] ?? '',
            'images_count' => $this->task['images_count'],
        ]);

        msg_success('Task has been successfully created.');

        return redirect()->route('tasks.index');
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'task.title' => [
                'required',
                'string'
            ],
            'task.description' => [
                'nullable',
                'string'
            ],
            'task.images_count' => [
                'required',
                'integer',
                'min:1'
            ],
        ];
    }
}
