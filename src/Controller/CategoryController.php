<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Form\SearchPlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/new", name="category_create")
     * @Route("/category/{id}/edit", name="category_edit")
     */
    public function form_category(Categorie $categorie = null, Request $request, EntityManagerInterface $manager, PlatRepository $platRepository)
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

        if(!$categorie) {
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        $repoCat = $this->getDoctrine()->getRepository(Categorie::class);
        $repoPlat = $this->getDoctrine()->getRepository(Plat::class);

        $categories = $repoCat->findAll();
        $plats = $repoPlat->findAll();

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($categorie);
            $manager->flush();

            return $this->redirectToRoute('menu_edit', [
                'controller_name' => 'FabulousMenuController',
                'categories' => $categories,
                'plats' => $plats,
                'search_form' => $searchPlatForm->createView(),
                'plats_search' => $platsSearch
            ]);
        }

        return $this->render('fabulous_menu/create_categorie.html.twig', [
            'formCategorie' => $form->createView(),
            'editMode' => $categorie->getId() !== null,
            'controller_name' => 'CategoryController',
            'categories' => $categories,
            'plats' => $plats,
            'search_form' => $searchPlatForm->createView(),
            'plats_search' => $platsSearch
        ]);
    }

    /**
     * @Route("/category/{id}/delete", name="category_delete")
     */
    public function delete_category(Categorie $categorie)
    {
        // Le gestionnaire d'entité retire la catégorie de la base de données
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return $this->redirectToRoute('menu_edit');
    }
}
