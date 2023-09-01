<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "creating" event.
     */
    public function creating(Category $category): void
    {
        $category->slug = Str::slug($category->name);
    }

    /**
     * Handle the Category "updating" event.
     */
    public function updating(Category $category): void
    {
        $category->slug = Str::slug($category->name);
    }
}
