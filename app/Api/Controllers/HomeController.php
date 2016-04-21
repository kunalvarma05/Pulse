<?php
namespace Pulse\Api\Controllers;

class HomeController extends BaseController
{

    public function index()
    {
        $data = ['name' => config('api.name'), 'version' => config('api.version')];
        return $this->response->array($data);
    }
}
