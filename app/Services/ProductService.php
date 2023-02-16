<?php

namespace App\Services;

use App\Repositories\{ProductRepository};
use App\Core\Services\CoreService;

class ProductService extends CoreService
{
    public function __construct(ProductRepository $repository)
    {
        parent::__construct($repository);
    }
}
