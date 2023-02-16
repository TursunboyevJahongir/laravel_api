<?php

namespace App\Http\Controllers;

use App\Core\Http\Controllers\ApiResourceController as Controller;
use App\Http\Requests\{ProductCreateRequest, ProductUpdateRequest};
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(ProductService $service)
    {
        parent::__construct($service);
    }

    protected array|string $storeRelations = ['mainImage', 'video', 'images'];
}
