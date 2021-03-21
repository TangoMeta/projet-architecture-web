<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Plat;
use App\Form\CategorieType;
use App\Form\FilterPlatType;
use App\Form\PlatType;
use App\Form\SearchPlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FabulousMenuController extends AbstractController
{
    /**
     * @Route("/", name="menu")
     */
    public function menu(Request $request, PlatRepository $platRepository)
    {
        $repoCat = $this->getDoctrine()->getRepository(Categorie::class);
        $repoPlat = $this->getDoctrine()->getRepository(Plat::class);

        $categories = $repoCat->findAll();
        $plats = $repoPlat->findAll();

        $searchPlatForm = $this->createForm(SearchPlatType::class);
        $filterPlatForm = $this->createForm(FilterPlatType::class);

        $platsSearch = [];

        if($searchPlatForm->handleRequest($request)->isSubmitted() && $searchPlatForm->isValid()) {
            $criteria = $searchPlatForm->getData();
            $platsSearch = $platRepository->searchPlat($criteria);
        }
        elseif ($filterPlatForm->handleRequest($request)->isSubmitted() && $filterPlatForm->isValid()) {
            $filtersList = ['végétarien', 'vegan', 'pescetarien', 'soja', 'poisson', 'fruits à coques', 'gluten', 'mollusques', 'céléri', 'crustacés', 'oeuf', 'arachide', 'lupin', 'moutarde', 'produits laitiers'];
            $criteria = $filterPlatForm->getData();
            $activeFilters = [];
            $i = 0;
            foreach ($criteria as $key => $value) {
                if ($value) {
                    $activeFilters[$key] = $filtersList[$i];
                }
                else {
                    $activeFilters[$key] = null;
                }
                $i++;
            }
            $platsSearch = $platRepository->filterPlat($activeFilters);
        }

        return $this->render('fabulous_menu/menu.html.twig', [
            'controller_name' => 'FabulousMenuController',
            'categories' => $categories,
            'plats' => $plats,
            'search_form' => $searchPlatForm->createView(),
            'filter_form' => $filterPlatForm->createView(),
            'plats_search' => $platsSearch
        ]);
    }
    
    /**
     * @Route("/menu/edit", name="menu_edit")
     */
    public function menu_edit(Request $request, PlatRepository $platRepository)
    {
        $repoCat = $this->getDoctrine()->getRepository(Categorie::class);
        $repoPlat = $this->getDoctrine()->getRepository(Plat::class);

        $categories = $repoCat->findAll();
        $plats = $repoPlat->findAll();

        $searchPlatForm = $this->createForm(SearchPlatType::class);
        $filterPlatForm = $this->createForm(FilterPlatType::class);

        $platsSearch = [];

        if($searchPlatForm->handleRequest($request)->isSubmitted() && $searchPlatForm->isValid()) {
            $criteria = $searchPlatForm->getData();
            $platsSearch = $platRepository->searchPlat($criteria);
        }
        elseif ($filterPlatForm->handleRequest($request)->isSubmitted() && $filterPlatForm->isValid()) {
            $filtersList = ['végétarien', 'vegan', 'pescetarien', 'soja', 'poisson', 'fruits à coques', 'gluten', 'mollusques', 'céléri', 'crustacés', 'oeuf', 'arachide', 'lupin', 'moutarde', 'produits laitiers'];
            $criteria = $filterPlatForm->getData();
            $activeFilters = [];
            $i = 0;
            foreach ($criteria as $key => $value) {
                if ($value) {
                    $activeFilters[$key] = $filtersList[$i];
                }
                else {
                    $activeFilters[$key] = null;
                }
                $i++;
            }
            $platsSearch = $platRepository->filterPlat($activeFilters);
        }

        return $this->render('fabulous_menu/menu_edit.html.twig', [
            'controller_name' => 'FabulousMenuController',
            'categories' => $categories,
            'plats' => $plats,
            'search_form' => $searchPlatForm->createView(),
            'filter_form' => $filterPlatForm->createView(),
            'plats_search' => $platsSearch
        ]);
    }

    /**
     * @Route("/menu/new", name="plate_create")
     * @Route("/menu/{id}/edit", name="plate_edit")
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
        
        if(!$plat) {
            $plat = new Plat();
        }

        $form = $this->createForm(PlatType::class, $plat);

        $form->handleRequest($request);

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
            'controller_name' => 'FabulousMenuController',
            'categories' => $categories,
            'plats' => $plats,
            'search_form' => $searchPlatForm->createView(),
            'plats_search' => $platsSearch
        ]);
    }


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

            return $this->redirectToRoute('category_edit', [
                'id' => $categorie->getId(),
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
            'controller_name' => 'FabulousMenuController',
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
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return $this->redirectToRoute('menu_edit');
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
