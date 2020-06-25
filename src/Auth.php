<?php

namespace App;

class Auth
{
    static function check()
    {
        if (!isset($_GET['admin'])) {
            //throw new \Exception("Unauthorized");
        }
    }
}
