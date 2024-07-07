<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class FilterByLabel
{
    public function handle(Builder $query, Closure $next)
    {
        if(request()->has('label') && request('label') !== 'All') {
            $query->whereJsonContains('labels', request('label'));
        }

        return $next($query);
    }
}
