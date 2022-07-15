<?php

namespace App\Http\Controllers\Api;

//use App\Core\Http\Controllers\CoreController as Controller;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductCreateRequest;
use App\Http\Requests\Api\ProductUpdateRequest;
use App\Http\Resources\Api\AdminAllProductsResource;
use App\Http\Resources\Api\AdminProductResource;
use App\Http\Resources\Api\AdminProductShowResource;
use App\Http\Resources\Api\PaginationResourceCollection;
use App\Http\Resources\Api\ProductResource;
use App\Http\Resources\Api\ProductShowResource;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function products(Request $request): JsonResponse
    {
        $size = $request->per_page ?? config('app.per_page');
        $orderBy = $request->orderby ?? "position";
        $sort = $request->sort ?? "DESC";
        $min = $request->min_price ?? null;
        $max = $request->min_price ?? null;

        $data = $this->service->products($size, $orderBy, $sort, $min, $max);
        return $this->success(__('messages.success'), new PaginationResourceCollection($data['products'],
            AdminAllProductsResource::class), $data['append']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            return $this->success(__('messages.success'), new ProductShowResource($this->service->show($id)));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function AdminShow(Product $id): JsonResponse
    {
        return $this->success(__('messages.success'), new AdminProductShowResource($id));
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
    public function create(ProductCreateRequest $request): JsonResponse
    {
        return $this->success(__('messages.success'), new AdminProductShowResource($this->service->create($request->validated())));
    }

    /**
     * Display a listing of the resource.
     *
     * @param ProductUpdateRequest $request
     * @param Product $id
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, Product $id): JsonResponse
    {
        $Category = $this->service->update($request->validated(), $id);
        return $this->success(__('messages.success'), new AdminProductShowResource($Category));
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
            AdminProductResource::class), $data['append']);
    }
}
