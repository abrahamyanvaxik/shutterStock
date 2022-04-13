@props(['key'])

<div class="input-group mb-3">
    <textarea
        {{ $attributes }}
        wire:model.defer="{{ $key }}"
        rows="5"
        name="{{ $key }}"
        class="form-control @errorClass($key)"
        placeholder="{{ trans("validation.attributes.$key") }}"
    ></textarea>

    <x-inputs.error field="{{ $key }}" />
</div>
