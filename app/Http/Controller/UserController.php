<?php

namespace App\Http\Controller;

use App\Model\User;
use Core\View;

class UserController extends Controller
{
    public function index()
    {
        $users = User::getUsers();
        var_dump($users);
    }

}