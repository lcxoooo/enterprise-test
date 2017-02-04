<?php

namespace App\Http\Controllers\Api\V1;

use App\Libraries\ApiResponses\ApiResponse;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

/**
 * Class BaseController
 *
 * @package App\Http\Controllers\Api\V1
 */
class BaseController extends Controller
{
    use Helpers, ApiResponse;
}
