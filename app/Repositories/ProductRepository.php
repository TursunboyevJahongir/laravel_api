<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryContract;
use App\Core\Repositories\CoreRepository;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends CoreRepository implements ProductRepositoryContract
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
