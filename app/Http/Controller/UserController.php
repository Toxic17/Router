<?php

namespace App\Http\Controller;

use Core\Route;
use Core\View;

class UserController extends Controller
{
    public function go($posts)
    {
        return View::view('main.index',['ID'=>$posts]);
    }

    public function send()
    {
        Route::redirect("/test");
    }

    public function index()
    {
        echo 'this is index';
    }

    public function index_test()
    {
        echo 'this is index test';
    }

    public function index_test_1()
    {
        echo 'this is index test 1';
    }


}