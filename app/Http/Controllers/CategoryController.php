<?php

namespace App\Http\Controllers;

use App\Core\Http\Controllers\ApiResourceController as Controller;
use App\Http\Requests\{CategoryCreateRequest, CategoryUpdateRequest};
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(CategoryService $service)
    {
        parent::__construct($service,new CategoryCreateRequest,new CategoryUpdateRequest);
    }
}
