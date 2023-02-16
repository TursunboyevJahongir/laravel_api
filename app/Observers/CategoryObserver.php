<?php

namespace App\Observers;

use App\Events\DestroyFiles;
use App\Events\UpdateImage;
use App\Models\Category;

class CategoryObserver
{
    public function deleting(Category $category)
    {
        DestroyFiles::dispatch($category->ico?->id);
    }

    public function created(Category $category)
    {
        if (request()->hasFile('ico')) {
            UpdateImage::dispatch(request('ico'), $category->ico());
        }
    }

    public function updated(Category $category)
    {
        if (request()->hasFile('ico')) {
            UpdateImage::dispatch(request('ico'), $category->ico());
        }
    }
}
