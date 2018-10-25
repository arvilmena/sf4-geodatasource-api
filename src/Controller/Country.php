<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Country extends AbstractController
{
    public function index()
    {
        return new Response('hello world!');
    }
}