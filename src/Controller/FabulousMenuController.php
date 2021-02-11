<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FabulousMenuController extends AbstractController
{
    /**
     * @Route("/fabulous/menu", name="fabulous_menu")
     */
    public function index(): Response
    {
        return $this->render('fabulous_menu/index.html.twig', [
            'controller_name' => 'FabulousMenuController',
        ]);
    }
}
