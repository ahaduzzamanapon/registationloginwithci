<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Welcome extends BaseController
{
    public function index()
    {
        echo "Welcome";
    }

    public function myname($name, $age)
    {
        echo "Welcome $name. You are $age years old.";
    }

    public function sub($a, $b)
    {
        $result = $a + $b;
        echo "The result of adding $a and $b is $result.";
    }

    public function __call($method, $arguments)
    {
        if ($method === 'index') {
            $this->index();
        } else {
            $this->index();
        }
    }
}
