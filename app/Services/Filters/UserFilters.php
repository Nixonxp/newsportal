<?php


namespace App\Services\Filters;


class UserFilters extends QueryFilter
{
    public function role($value = null)
    {
        if (!isset($value)) return;

        return $this->builder->where('role_id', $value);
    }

    public function name($value = null)
    {
        if (!isset($value)) return;

        return $this->builder->where('name', 'ilike', '%' .$value . '%');
    }

    public function email($value = null)
    {
        if (!isset($value)) return;

        return $this->builder->where('email', 'ilike', '%' .$value . '%');
    }

    public function sort($value = null)
    {
        return match ($value) {
            'old' => $this->builder->orderBy('id' ,'DESC'),
            'new' => $this->builder->orderBy('id' ,'ASC'),
            default => $this->builder->orderBy('id' ,'ASC'),
        };
    }
}
