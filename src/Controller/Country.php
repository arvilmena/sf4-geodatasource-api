<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class Country
{
    public function index()
    {
        return new Response('hello world!');
    }
}