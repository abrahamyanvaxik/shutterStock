<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;

class EditTaskComponent extends Component
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
        return view('tasks.edit')->extends('layouts.app');
    }

    /**
     * Update existing task.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $this->validate();

        // edit-review
        $this->task->save();

        msg_success('Task has been successfully updated.');

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
