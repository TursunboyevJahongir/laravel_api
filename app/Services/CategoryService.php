<?php


namespace App\Services;

use App\Contracts\CategoryServiceContract;
use App\Contracts\CategoryRepositoryContract;
use App\Core\Services\CoreService;

class CategoryService extends CoreService implements CategoryServiceContract
{
    public function __construct(CategoryRepositoryContract $repository)
    {
        parent::__construct($repository);
    }
}
