<?php


namespace App\Services;

use App\Core\Services\CoreService as Service;
use App\Repositories\BookRepository;

class BookService extends Service
{
    public function __construct(BookRepository $repository)
    {
        parent::__construct($repository);
    }
}
