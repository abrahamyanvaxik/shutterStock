<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;

class IndexTaskComponent extends Component
{
    use HasTable, HasLivewireAuth;

    /** @var string */
    public $sortField = 'title';

    // index-review
    /** @var array */
    protected $queryString = ['perPage', 'sortField', 'sortDirection', 'search'];

    /** @var array */
    protected $allowedRoles = [];

    /** @var array */
    protected $listeners = ['entity-deleted' => 'render'];

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $tasks = Task::query();
        if (!auth()->user()->isAdmin()) {
            if (Task::where('user_id', auth()->user()->getAuthIdentifier())->count()) {
                $tasks = $tasks->where(['user_id' => auth()->user()->getAuthIdentifier()]);
            } else {
                $tasks = $tasks->where(['user_id' => null]);
            }
        }
        $tasks = $tasks->filter([
            'search' => $this->search,
            'orderByField' => [$this->sortField, $this->sortDirection],
        ])->paginate($this->perPage);

        return view('tasks.index', ['tasks' => $tasks])
            ->extends('layouts.app');
    }
}
