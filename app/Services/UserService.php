<?php


namespace App\Services;

use App\Repositories\UserRepository;
use App\Core\Services\CoreService;

class UserService extends CoreService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }
}
