<?php


namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Core\Services\CoreService;

class CategoryService extends CoreService
{
    public function __construct(CategoryRepository $repository)
    {
        parent::__construct($repository);
    }
}
