<div>
    @can('for-route', ['tasks.delete', $this->task])
        <a
            x-data
            x-on:click.prevent="confirm('Are you sure?') && $wire.destroy()"
            href="#"
            class="btn-default"
        >
            <span class='fa fa-times'></span>
        </a>
    @endcan
</div>
