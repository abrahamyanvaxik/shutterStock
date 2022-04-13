@section('title')
    Start Task
@endsection

@section('content-header')
<x-content-header>
    Start Task
</x-content-header>
@endsection

<x-savings.content>
    <x-slot name="card_header">
        <h3 class="card-title">Start Task</h3>
        <a href="{{ route('tasks.index') }}" class="float-right">Back</a>
    </x-slot>

    <x-slot name="card_body">
        <div class="mb-5">
            <div>{{ $task->title }}</div>
            <div>{!! $task->description !!}</div>
            <div>
                <strong>Need Images for completing Task:</strong> {{ $task->images_count }} (Added: {{ $task->images()->count() }})
            </div>
            @if($task->images()->count() >= $task->images_count)
                <div class="mt-2">
                    <button wire:click="completeTask()"
                            type="button"
                            class="btn btn-inline btn-success">
                        Complete
                    </button>
                </div>
            @endif
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
                                Image
                            </th>
                            <th class="sorting">
                                Description
                            </th>
                            <th class="sorting">
                                Add
                            </th>
                        </tr>
                    </x-slot>

                    <x-slot name="tbody" class="table-responsive">
                        @forelse($images as $image)
                            <tr class="@if($loop->odd) odd @endif">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img class="img-fluid img-responsive" src="{{ $image['assets']['huge_thumb']['url'] }}" alt="{{ $image['description'] }}">
                                </td>
                                <td>{{ $image['description'] }}</td>
                                <td>
                                    @if(!in_array($image['id'], $task->images()->get()->pluck('image_id')->toArray()))
                                        <a wire:click="addImageToTask({{ $image['id'] }}, '{{ $image['assets']['huge_thumb']['url'] }}', '{{ $image['description'] }}')">
                                            <span class="fas fa-plus"></span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No results.</td>
                            </tr>
                        @endforelse
                    </x-slot>

                </x-tables.table>

                <div class="row">
                    <x-tables.entries-data :data="$images" />

                    <x-tables.pagination :data="$images" />
                </div>

            </div>
        </div>
    </x-slot>

</x-savings.content>