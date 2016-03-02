<?php

namespace App\Http\Controllers;

use \App\Http\Requests;
use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        return "Welcome to Pulseapp";
    }
}