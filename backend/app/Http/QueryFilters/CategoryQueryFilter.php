<?php

namespace App\Http\QueryFilters;

class CategoryQueryFilter extends QueryFilter
{
    protected $searchable = [
        'name',
        'slug',
    ];

    protected $sortable = [
        'name', 'slug', 'created_at'
    ];

    protected $defaultSortBy = 'updated_at';

    public function onlyParent()
    {
        return $this->queryBuilder
            ->whereNull('parent_id')
            ->with('children');
    }

    public function search($value)
    {
        parent::search($value);
        $this->queryBuilder->orWhere(function ($query) use ($value) {
            $query->whereHas('children', function ($childrenQuery) use ($value) {
                $childrenQuery->where('name', 'like', "%{$value}%");
                $childrenQuery->orWhere('slug', 'like', "%{$value}%");
            });
        });
    }

    public function isPublished($value)
    {
        $this->queryBuilder->published($value);
    }

    public function parentId($value)
    {
        if (!$value) {
            return;
        }

        $this->queryBuilder
            ->where('parent_id', $value);
    }
}
