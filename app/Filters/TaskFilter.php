<?php

namespace App\Filters;

class TaskFilter extends Filter
{
    // index-review
    /**
     * Filter by name field.
     *
     * @param  mixed  $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search($term = null)
    {
        $this->when($term, function ($query, $term) {
            $query->where('title', 'LIKE', "%$term%");
        });

        return $this;
    }
}
