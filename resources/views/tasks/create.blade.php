<div>
@section('title')
    Create New Task
@endsection

@section('content-header')
<x-content-header>
    Create New Task
</x-content-header>
@endsection

<x-savings.content>
    <x-slot name="card_header">
        <h3 class="card-title">Create New Task</h3>
        <a href="{{ route('tasks.index') }}" class="float-right">Back</a>
    </x-slot>

    <x-slot name="card_body">
        <form method="POST" wire:submit.prevent="store">
            @csrf

            <x-inputs.text key="task.title"
                           required="required"
                           placeholder="{{ trans('validation.attributes.name') }}"
                           autofocus />

            <x-inputs.text type="number"
                           key="task.images_count"
                           required="required"
                           min="1"
                           pattern="[0-9]*"
                           placeholder="{{ trans('validation.attributes.count') }}"/>

            <x-inputs.textarea key="task.description"
                               placeholder="{{ trans('validation.attributes.description') }}"/>

            <div class="row">
                <div class="offset-8 col-4">
                    <x-inputs.button text="Save" class="btn-success" />
                </div>
            </div>
        </form>

    </x-slot>
</x-savings.content>
