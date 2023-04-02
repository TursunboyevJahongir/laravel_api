<?php

namespace App\Http\Controllers;

use App\Core\Helpers\ResponseCode;
use App\Services\BookService;
use App\Http\Requests\{BookCreateRequest, BookUpdateRequest};
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use App\Core\Http\Controllers\CoreController as Controller;

class BookController extends Controller
{
    public function __construct(BookService $service)
    {
        parent::__construct($service);
        $this->authorizeResource(Book::class);
    }

    public function index(): JsonResponse
    {
        try {
            $books = $this->service->index();

            return $this->responseWith(compact('books'));
        } catch (\Exception $e) {
            return $this->errorException($e);
        }
    }

    public function show(Book $book): JsonResponse
    {
        try {
            $book = $this->service->show($book);

            return $this->responseWith(compact('book'));
        } catch (\Exception $e) {
            return $this->errorException($e);
        }
    }

    public function store(BookCreateRequest $request): JsonResponse
    {
        try {
            $book = $this->service->create($request);

            return $this->responseWith(compact('book'), ResponseCode::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorException($e);
        }
    }

    public function update(Book $book, BookUpdateRequest $request): JsonResponse
    {
        try {
            $this->service->update($book, $request);

            return $this->noContent();
        } catch (\Exception $e) {
            return $this->errorException($e);
        }
    }

    public function destroy(Book $book): JsonResponse
    {
        try {
            $this->service->delete($book);

            return $this->noContent();
        } catch (\Exception $e) {
            return $this->errorException($e);
        }
    }
}
