<?php
namespace Pulse\Http\Controllers;

use Pulse\Utils\Helpers;
use Pulse\Http\Requests;
use Illuminate\Http\Request;
use Pulse\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('main');
    }

    public function authCallback(Request $request, $provider) {

        if( !$request->has('code') || !$request->has('state') ) {
            abort(402, "Invalid Request!");
        }

        $params = [
            'code' => $request->get('code'),
            'state' => $request->get('state'),
        ];

        $url = Helpers::url("/#!/dashboard/auth-callback/{$provider}", $params);

        return redirect($url);

    }

    public function passwordReset(Request $request, $token) {
        $params = ['token' => $token];
        $url = Helpers::url("/#!/reset-password", $params);

        return redirect($url);
    }

}
