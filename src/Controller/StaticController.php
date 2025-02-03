<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class StaticController extends AbstractController
{
    public function home(): Response
    {
        return $this->render('Static/home.html.twig', [
            'title' => 'Inicio'
        ]);
    }

}
