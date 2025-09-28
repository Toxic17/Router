<?php

namespace App\Http\Controller;

use App\Model\User;
use Core\ResponseFactory;


class UserController extends Controller
{
    public function index()
    {
        $response = new ResponseFactory();
        $response->view('main.index');
    }

    public function test()
    {
        $response = new ResponseFactory();
        $response->json(['test' => 'test']);
    }

    public function post()
    {
        var_dump($this->post_params);
    }
}