<?php

namespace App\Http\Controllers\Concerns;

trait FiltersByUser
{
    /**
     * Apply user filter to a query builder if user is authenticated
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User|null $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyUserFilter($query, $user = null)
    {
        $user = $user ?? auth()->user();
        
        if ($user) {
            $query->where('user_id', $user->id);
        }
        
        return $query;
    }
    
    /**
     * Get the current authenticated user
     * 
     * @return \App\Models\User|null
     */
    protected function getCurrentUser()
    {
        return auth()->user();
    }
}
