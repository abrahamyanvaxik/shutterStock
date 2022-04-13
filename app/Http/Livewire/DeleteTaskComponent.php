<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DeleteTaskComponent extends Component
{
    use CanFlash, HasLivewireAuth;

    /** @var \App\Models\Task */
    public $task;

    public function render()
    {
        return view('tasks.delete');
    }

    /**
     * Delete task.
     *
     * @return void
     */
    public function destroy()
    {
        // delete-review

        $this->task->delete();

        $this->emit('entity-deleted');

        $this->dispatchFlashSuccessEvent('Task has been successfully deleted.');
    }
}
