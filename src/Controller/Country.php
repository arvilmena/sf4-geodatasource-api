<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Country extends AbstractController
{
    public function index()
    {
        
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        
        $countries = $this->getDoctrine()
            ->getRepository(\App\Entity\Country::class)->findAll();

        $jsonContent = $serializer->serialize($countries, 'json', array('attributes' => array('ccIso', 'ccFips', 'countryName')));

        $response = new Response();
        $response->setContent( $jsonContent );
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}