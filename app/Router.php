<?php

namespace App;

use Pecee\SimpleRouter\SimpleRouter;

class Router extends SimpleRouter
{
    public static function start(): void
    {
        require '../routes/routes.php';

        parent::start();
    }
}
