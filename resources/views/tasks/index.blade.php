@section('title')
    Tasks
@endsection

@section('content-header')
<x-content-header>
    Tasks
</x-content-header>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of Tasks</h3>
                @can('for-route')
                    <a href="{{ route('tasks.create') }}" class="float-right">Add New</a>
                @endcan
            </div>

            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <x-tables.per-page />

                        <!-- div for extra filters -->
                        <div class="col-md-3 col-sm-12 form-group"></div>
                        <!-- end div for extra filters -->

                        <x-tables.search />
                    </div>

                    <x-tables.table>

                        <x-slot name="thead_tfoot">
                            <tr>
                                <th class="sorting">
                                    #
                                </th>
                                <th class="sorting">
                                    <a href="#" wire:click.prevent="sortBy('title')">Name</a>
                                    <x-tables.sort-by :sortField="$sortField" :sortDirection="$sortDirection" field="title" />
                                </th>
                                <th class="sorting">
                                    <a href="#" wire:click.prevent="sortBy('images_count')">{{ trans('validation.attributes.count') }}</a>
                                    <x-tables.sort-by :sortField="$sortField" :sortDirection="$sortDirection" field="images_count" />
                                </th>
                                <th class="sorting">
                                    <a href="#" wire:click.prevent="sortBy('completed')">Completed</a>
                                    <x-tables.sort-by :sortField="$sortField" :sortDirection="$sortDirection" field="completed" />
                                </th>
                                <th class="sorting">
                                    <a href="#" wire:click.prevent="sortBy('created_at')">Created</a>
                                    <x-tables.sort-by :sortField="$sortField" :sortDirection="$sortDirection" field="created_at" />
                                </th>

                                @if(auth()->user()->isAdmin())
                                    <th class="sorting">
                                        Edit
                                    </th>
                                @endif
                                @if(!auth()->user()->isAdmin())
                                    <th class="sorting">
                                        Start
                                    </th>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <th class="sorting">
                                        Delete
                                    </th>
                                @endif
                            </tr>
                        </x-slot>

                        <x-slot name="tbody">
                            @forelse($tasks as $task)
                                <tr class="@if($loop->odd) odd @endif">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->images_count }}</td>
                                    <td>{{ $task->completed ? 'Yes' : 'No' }}</td>
                                    <td>{{ $task->created_at->format('d/m/Y') }}</td>
                                    @if(auth()->user()->isAdmin())
                                        <td>
                                            @can('for-route', ['tasks.edit', $task])
                                                <a href="{{ route('tasks.edit', $task) }}"><span class="fas fa-edit"></span></a>
                                            @endcan
                                        </td>
                                    @endif
                                    @if(!auth()->user()->isAdmin())
                                        <td>
                                            @can('for-route', ['tasks.start', $task])
                                                <a href="{{ route('tasks.start', $task) }}"><span class="fas fa-play"></span></a>
                                            @endcan
                                        </td>
                                    @endif
                                    @if(auth()->user()->isAdmin())
                                        <td>
                                            <livewire:delete-task-component :task="$task" :key="'task-'.$task->id" />
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No results.</td>
                                </tr>
                            @endforelse
                        </x-slot>

                    </x-tables.table>

                    <div class="row">
                        <x-tables.entries-data :data="$tasks" />

                        <x-tables.pagination :data="$tasks" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
