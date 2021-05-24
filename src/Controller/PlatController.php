<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Entity\Categorie;
use App\Form\SearchPlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlatController extends AbstractController
{
    /**
     * @Route("/menu/new", name="plate_create")
     * @Route("/menu/{id}/edit", name="plate_edit")
     * Affiche le formulaire de création/édition d'un plat
     */
    public function form_plat(Plat $plat = null, Request $request, EntityManagerInterface $manager, PlatRepository $platRepository)
    {
        $repoCat = $this->getDoctrine()->getRepository(Categorie::class);
        $repoPlat = $this->getDoctrine()->getRepository(Plat::class);

        $categories = $repoCat->findAll();
        $plats = $repoPlat->findAll();

        $searchPlatForm = $this->createForm(SearchPlatType::class);

        $platsSearch = [];

        if($searchPlatForm->handleRequest($request)->isSubmitted() && $searchPlatForm->isValid()) {
            $criteria = $searchPlatForm->getData();
            $platsSearch = $platRepository->searchPlat($criteria);
        }
        
        // Si le paramètre plat est null, alors on est en mode création, et une entité Plat est créée
        if(!$plat) {
            $plat = new Plat();
        }

        // Création du formulaire de création/édition du plat
        $form = $this->createForm(PlatType::class, $plat);

        $form->handleRequest($request);

        /* Si le formulaire est envoyé et qu'il est valide,
            alors l'entity manager fait persister la donnée créée */ 
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($plat);
            $manager->flush();

            return $this->redirectToRoute('menu_edit', [
                'id' => $plat->getId(),
                'controller_name' => 'FabulousMenuController',
                'categories' => $categories,
                'plats' => $plats,
                'search_form' => $searchPlatForm->createView(),
                'plats_search' => $platsSearch
            ]);
        }

        return $this->render('fabulous_menu/create_plat.html.twig', [
            'formPlat' => $form->createView(),
            'editMode' => $plat->getId() !== null,
            'controller_name' => 'PlatController',
            'categories' => $categories,
            'plats' => $plats,
            'search_form' => $searchPlatForm->createView(),
            'plats_search' => $platsSearch
        ]);
    }

    /**
     * @Route("/plat/{id}/delete", name="plat_delete")
     */
    public function delete_plat(Plat $plat)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($plat);
        $em->flush();

        return $this->redirectToRoute('menu_edit');
    }
}