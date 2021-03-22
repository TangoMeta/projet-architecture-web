<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Entity\Categorie;
use App\Form\FilterPlatType;
use App\Form\SearchPlatType;
use App\Repository\PlatRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FabulousMenuController extends AbstractController
{
    /**
     * @Route("/", name="menu")
     * Affiche le menu pour le client
     */
    public function menu(Request $request, PlatRepository $platRepository)
    {
        // Récupère les repository des entités
        $repoCat = $this->getDoctrine()->getRepository(Categorie::class);
        $repoPlat = $this->getDoctrine()->getRepository(Plat::class);

        // Récupère les données depuis la base de données par l'envoi d'une requête
        $categories = $repoCat->findAll();
        $plats = $repoPlat->findAll();

        // Création des formulaires de recherche et de filtrage
        $searchPlatForm = $this->createForm(SearchPlatType::class);
        $filterPlatForm = $this->createForm(FilterPlatType::class);

        // Initialisation de la liste de récupération des résultats de la recherche
        $platsSearch = [];

        /* Si une recherche est faite, récupération de ce qui est saisi, et passage en
        paramètre de cet argument pour envoyer la requête via le repository */
        if($searchPlatForm->handleRequest($request)->isSubmitted() && $searchPlatForm->isValid()) {
            $criteria = $searchPlatForm->getData();
            $platsSearch = $platRepository->searchPlat($criteria);
        }
        /* Si des filtres sont appliqués,
        récupération des filtres,
        puis traitement de ceux-ci afin de les appliquer convenablement,
        puis envoi de la requête via le repository */
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
     * Affiche le menu pour le restaurateur, avec une possibilité d'édition de celui-ci
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
}
