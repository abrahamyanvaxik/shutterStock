<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ImageTask extends Pivot
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'image_id' => 'integer',
        'task_id' => 'integer',
    ];
}
