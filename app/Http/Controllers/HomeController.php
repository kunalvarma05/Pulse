<?php

namespace Pulse\Http\Controllers;

use \Pulse\Http\Requests;
use Illuminate\Http\Request;
use \Pulse\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('main');
    }
}
