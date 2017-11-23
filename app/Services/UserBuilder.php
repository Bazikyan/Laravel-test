<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Auth;


class UserBuilder
{
    private $query = null;
    private $projects = null;
    private static $instance = null;

    private function __construct()
    {
        $this->query = User::query();

        if (Auth::check()) {
            $user = Auth::user();
            //dd($user->projects);
            $this->projects = $user->projects;
        }
    }

    public static function __callStatic($name, $arguments)
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return call_user_func([self::$instance, $name]);
    }

    private function test()
    {
        dd($this->query);
    }

    private function getUserProjects()
    {
//        dd($this->projects);
        return $this->projects;
    }

}