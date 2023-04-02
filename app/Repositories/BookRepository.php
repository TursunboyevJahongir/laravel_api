<?php

namespace App\Repositories;

use App\Core\Repositories\CoreRepository as Repository;
use App\Models\Book;

class BookRepository extends Repository
{
    public function __construct(Book $model)
    {
        parent::__construct($model);
    }
}
