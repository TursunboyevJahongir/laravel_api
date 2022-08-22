<?php

namespace App\Repositories;

use App\Contracts\CategoryRepositoryContract;
use App\Core\Repositories\CoreRepository;
use App\Models\Category;

class CategoryRepository extends CoreRepository implements CategoryRepositoryContract
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
}
