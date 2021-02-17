<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FabulousMenuController extends AbstractController
{
    /**
     * @Route("/", name="menu")
     */
    public function menu()
    {
        return $this->render('fabulous_menu/menu.html.twig');
    }
    
    /**
     * @Route("/menu/edit", name="menu_edit")
     */
    public function menu_edit(): Response
    {
        return $this->render('fabulous_menu/menu_edit.html.twig', [
            'controller_name' => 'FabulousMenuController',
        ]);
    }
}
