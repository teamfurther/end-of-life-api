<?php

use App\Http\Controllers\ApiController;
use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::post('/', [ApiController::class, 'index']);
