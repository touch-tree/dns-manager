<?php

namespace App\Framework;

use App\Framework\Base\Container;
use App\Framework\Http\HeaderBag;

class App
{
    public function register(Container $container)
    {
        // these should do dashboard
        $container::bind(HeaderBag::class, function () {
            return new HeaderBag();
        });
    }
}