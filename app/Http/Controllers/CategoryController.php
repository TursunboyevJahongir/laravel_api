<?php

namespace App\Http\Controllers;

use App\Core\Http\Controllers\CoreController as Controller;
use App\Core\Http\Requests\GetAllFilteredRecordsRequest;
use App\Http\Requests\{CategoryCreateRequest, CategoryUpdateRequest};
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(CategoryService $service)
    {
        parent::__construct($service);
        $this->authorizeResource(Category::class, 'category');
    }

    public function index(GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $categories = $this->service->index($request);

        return $this->responseWith(compact('categories'));
    }

    public function show(Category $category, GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $category = $this->service->show($category, $request);

        return $this->responseWith(compact('category'));
    }


    public function store(CategoryCreateRequest $request): JsonResponse
    {
        try {
            $category = $this->service->create($request)->loadMissing('ico');

            return $this->responseWith(compact('category'), 201);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }

    public function update(Category $category, CategoryUpdateRequest $request): JsonResponse
    {
        try {
            $this->service->update($category, $request);

            return $this->responseWith(code: 204);
        } catch (\Exception $e) {
            return $this->responseWith(code: $e->getCode(), message: $e->getMessage());
        }
    }

    public function destroy($category): JsonResponse
    {
        $this->service->delete($category);

        return $this->responseWith(code: 204);
    }
}
