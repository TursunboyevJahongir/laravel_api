<?php


namespace App\Http\Controllers;

use App\Traits\HasJsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    use HasJsonResponse;
}
