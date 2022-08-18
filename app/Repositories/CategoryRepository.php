<?php

namespace App\Repositories;

use App\Contracts\CategoryRepositoryContract;
use App\Core\Repositories\CoreRepository;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository extends CoreRepository implements CategoryRepositoryContract
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function availability(Model|Builder $query): void
    {
        $query->when(!hasRole('superadmin'), function ($query) {
            $query->where('author_id', auth()->id());
        });
    }
}
