<?php

namespace App\Model;

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "users";
    protected $fillable = ["name","email"];
    static function getUsers()
    {
        return User::all();
    }
    
}