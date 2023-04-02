<?php

namespace Tests\Feature;

use App\Core\Test\Feature\ResourceTest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Book;

final class BookTest extends ResourceTest
{
    public function getRouteName(): string
    {
        return 'books';
    }

    public function getModel(): Model
    {
        return new Book();
    }

    // public function testStore() todo need to implement

    // public function testUpdate() todo need to implement
}
