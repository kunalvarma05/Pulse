<?php
namespace Pulse\Api\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;

class BaseController extends Controller
{
    use Helpers, ValidatesRequests;
}
