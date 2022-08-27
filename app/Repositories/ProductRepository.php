<?php

namespace App\Repositories;

use App\Core\Repositories\CoreRepository;
use App\Models\Product;
use Illuminate\Database\Eloquent\{Builder, Model};

class ProductRepository extends CoreRepository
{
    public function __construct(Product $model)
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
