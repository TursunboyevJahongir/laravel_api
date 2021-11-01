<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\ApiController;

use App\Http\Requests\api\ProductCreateRequest;
use App\Http\Requests\api\ProductUpdateRequest;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\ProductShowResource;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ProductController extends ApiController
{
    public function __construct(private ProductService $service)
    {
    }

    /**
     * @param ProductService $productService
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function myProducts(ProductService $productService, Request $request): JsonResponse
    {
        $size = $request->per_page ?? config('app.per_page');
        $orderBy = $request->orderby ?? "position";
        $sort = $request->sort ?? "DESC";
        $min = $request->min_price ?? null;
        $max = $request->max_price ?? null;

        $data = $productService->myProducts($size, $orderBy, $sort, $min, $max);
        return $this->success(__('messages.success'), new PaginationResourceCollection($data['products'],
            ProductResource::class), $data['append']);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $id
     * @return JsonResponse
     */
    public function show(Product $id): JsonResponse
    {
        return $this->success(__('messages.success'), new ProductShowResource($id));
    }

    /**
     * Display the specified resource.
     *
     * @param Product $id
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function similar(Product $id, Request $request): JsonResponse
    {
        $size = $request->per_page ?? config('app.per_page');
        $data = $this->service->similar($id, $size);
        return $this->success(__('messages.success'), new PaginationResourceCollection($data, ProductResource::class));
    }

    /**
     * Display a listing of the resource.
     *
     * @param ProductCreateRequest $request
     * @return JsonResponse
     */
    public function store(ProductCreateRequest $request): JsonResponse
    {
        $Category = $this->service->create($request->validated());
        return $this->success(__('messages.success'), new ProductShowResource($Category));
    }

    /**
     * Display a listing of the resource.
     *
     * @param ProductUpdateRequest $request
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request): JsonResponse
    {
        $Category = $this->service->update($request->validated());
        return $this->success(__('messages.success'), new ProductShowResource($Category));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Product $id
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Product $id): JsonResponse
    {
        try {
            $this->service->delete($id);
            return $this->success(__('messages.success'));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * @param string $search
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function search(string $search, Request $request): JsonResponse
    {
        $size = $request->per_page ?? config('app.per_page');
        $orderBy = $request->orderby ?? "position";
        $sort = $request->sort ?? "DESC";
        $min = $request->min_price ?? null;
        $max = $request->max_price ?? null;
        $search = rtrim($search, " \t.");
        $data = $this->service->search($search, $size, $orderBy, $sort, $min, $max);
        return $this->success(__('messages.success'), new PaginationResourceCollection($data['products'],
            ProductResource::class), $data['append']);
    }
}
