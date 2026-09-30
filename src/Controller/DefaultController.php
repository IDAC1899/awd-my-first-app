<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function about()
    {
        return new Response(
            'About page – produced by my first Symfony controller'
        );
    }
}