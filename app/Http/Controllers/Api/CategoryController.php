<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CategoryCreateRequest;
use App\Http\Requests\Api\CategoryUpdateRequest;
use App\Http\Requests\GetAllFilteredRecordsRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use App\Core\Http\Controllers\CoreController as Controller;

class CategoryController extends Controller
{
    public function __construct(CategoryService $service)
    {
        parent::__construct($service);
    }

    public function index(GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $categories = $this->service->get($request);

        return $this->responseWith(['categories' => $categories]);
    }

    public function show($category, GetAllFilteredRecordsRequest $request): JsonResponse
    {
        $category = $this->service->show($category, $request);

        return $this->responseWith(compact('category'));
    }


    public function create(CategoryCreateRequest $request): JsonResponse
    {
        try {
            $category = $this->service->create($request);

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

    public function delete($category): JsonResponse
    {
        $this->service->delete($category);

        return $this->responseWith(code: 204);
    }
}
