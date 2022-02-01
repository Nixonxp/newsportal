<?php


namespace App\Services\Filters;


class NewsPostFilters extends QueryFilter
{
    public function status($value = null)
    {
        if (!isset($value)) return;

        return match ($value) {
            'publicated' => $this->builder->where('is_published', true)->whereNull('deleted_at'),
            'draft' => $this->builder->where('is_published', false)->whereNull('deleted_at'),
            'deleted' => $this->builder->onlyTrashed(),
            default => null,
        };
    }

    public function sort($value = null)
    {
        if (!isset($value)) return;

        return match ($value) {
            'old' => $this->builder->orderBy('id' ,'ASC'),
            'updated_new' => $this->builder->orderBy('updated_at' ,'DESC'),
            default => $this->builder->orderBy('id' ,'DESC'),
        };
    }
}
