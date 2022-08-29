<?php

namespace App\Repositories;

use App\Core\Repositories\CoreRepository;
use App\Models\Product;

class ProductRepository extends CoreRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }
}
