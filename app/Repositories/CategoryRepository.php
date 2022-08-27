<?php

namespace App\Repositories;

use App\Core\Repositories\CoreRepository;
use App\Models\Category;

class CategoryRepository extends CoreRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
}
