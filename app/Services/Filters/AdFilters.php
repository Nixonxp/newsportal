<?php


namespace App\Services\Filters;


class AdFilters extends QueryFilter
{
    public function sort($value = null)
    {
        return match ($value) {
            'old' => $this->builder->orderBy('id' ,'asc'),
            'new' => $this->builder->orderBy('id' ,'desc'),
            default => $this->builder->orderBy('id' ,'ASC'),
        };
    }

    public function activity($value = null)
    {
        if (!isset($value)) return;

        return match ($value) {
            'active' => $this->builder->active(),
            'noactive' => $this->builder->notActive()
        };
    }

    public function type($value = null)
    {
        if (!isset($value)) return;

        return $this->builder->where('type', $value);
    }
}
