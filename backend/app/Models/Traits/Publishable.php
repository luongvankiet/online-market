<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait Publishable
{
    public function scopePublished($query, $value = true)
    {
        if ($value === 'false' || $value === false) {
            $query->whereNull('published_at');
        } else {
            $query->where(function ($query) {
                $query->whereNotNull('published_at')
                    ->orWhere('published_at', '<=', Carbon::now());
            });
        }
    }
}
