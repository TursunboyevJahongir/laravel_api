<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationResourceCollection extends ResourceCollection
{
    private string $concreteClass;

    /**
     * PaginationResourceCollection constructor.
     * @param $resource
     * @param string $concrete
     * @throws \Exception
     */
    public function __construct($resource, string $concrete)
    {
        parent::__construct($resource);
        if (!class_exists($concrete)) {
            throw new \Exception('Class not found');
        }
        $this->concreteClass = $concrete;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var LengthAwarePaginator|self $this
         */
        return [
            'data' => $this->concreteClass::collection($this->collection),
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl()
        ];
    }
}
