<?php

namespace App\Http\Livewire;

use App\Filters\TaskFilter;
use App\Models\Image;
use App\Models\Task;
use App\Services\ShutterStockApiClient;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Pagination\LengthAwarePaginator;

class StartTaskComponent extends Component
{
    use HasTable, HasLivewireAuth;

    /** @var \App\Models\Task */
    public Task $task;

    /** @var string */
    public $sortField = 'desription';

    /**
     * @var int
     */
    public $maxCount = 2000;

    /** @var array */
    protected $queryString = [
        'perPage',
        'sortField',
        'sortDirection',
        'search',
    ];

    /** @var array */
    protected $listeners = ['image-added' => 'render'];

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        abort_if($this->checkTasksUserId(), '403', '');
        if (!auth()->user()->isAdmin()) {
            $this->task->update(['user_id' => auth()->user()->getAuthIdentifier()]);
        }
        $images = $this->getShutterStockImages();

        return view('tasks.start',
            [
                'images' => $images,
            ]
        )->extends('layouts.app');
    }

    /**
     * @return bool|void
     */
    private function checkTasksUserId()
    {
        if (auth()->user()->isAdmin()) {
            return false;
        }

        if (Task::where('user_id', auth()->user()->getAuthIdentifier())->where('id', '!=', $this->task->id)->count()) {
            return true;
        }

        if (!$this->task->user_id) {
            return false;
        }

        if ($this->task->user_id && $this->task->user_id != auth()->user()->getAuthIdentifier()) {
            return true;
        }
    }

    private function getShutterStockImages()
    {
        $client = new ShutterStockApiClient();
        $imageResponse = $client->get('images/search', $this->setShutterStockImagesQuery());
        if ($imageResponse->getStatusCode() != 200) {
            return [];
        }
        return $this->paginateShutterStockImagesResult($imageResponse->getBody()->jsonSerialize());
    }

    private function setShutterStockImagesQuery(): array
    {
        $this->search ? $query['query'] = $this->search : $query['query'] = '';
        if ($this->perPage) {
            $query['per_page'] = $this->perPage;
        }
        if ($this->page) {
            $query['page'] = $this->page;
        }
        //$query['view'] = 'full';
        return $query;
    }

    private function paginateShutterStockImagesResult($images): LengthAwarePaginator
    {
        $total = min($images['total_count'], $this->maxCount);
        $perPage = $this->perPage;
        $currentPage = $this->page ?? 1;
        return new LengthAwarePaginator($images['data'], $total, $perPage, $currentPage);
    }

    /**
     * Reset pagination back to page one if search query is changed.
     *
     * @return void
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * @param int $imageId
     * @param string $imagePath
     * @param string $imageDescription
     * @return void
     */
    public function addImageToTask(int $imageId, string $imagePath, string $imageDescription)
    {
        if (!in_array($imageId, $this->task->images()->get()->pluck('image_id')->toArray())) {
            $image = [
                'image_id' => $imageId,
                'description' => $imageDescription,
                'path' => $imagePath,
            ];
            $imageCreated = Image::create($image)->id;

            $imageTaskPivot = [
                'image_id' => $imageCreated,
                'task_id' => $this->task->id,
            ];

            $this->task->addImage($imageTaskPivot);
        }

        $this->dispatchFlashSuccessEvent('Image has been successfully added to Task.');

        $this->emit('image-added');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function completeTask()
    {
        if ($this->task->images()->count() >= $this->task->images_count) {
            $this->task->update(['completed' => true]);

            msg_success('Task has been successfully completed.');

            return redirect()->route('tasks.index');
        }
    }
}
