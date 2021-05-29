<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Entity\Categorie;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Constraints\Length;

header("Access-Control-Allow-Origin: *");

class ApiPlatController extends AbstractController
{
    /**
     * @Route("/api/plat", name="api_plat_index", methods={"GET"})
     */
    public function index(PlatRepository $platRepository)
    {
        // Récupération des plats
        return $this->json($platRepository->findAll(), 200, [], ['groups' => 'plat:read']);
    }

    /**
     * @Route("/api/plat", name="api_plat_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em,
    ValidatorInterface $validator) : Response
    {
        // Création d'un plat

        $jsonRecu = $request->getContent();

        try {
            $plat = $serializer->deserialize($jsonRecu, Plat::class, 'json');
            $category = $this->getDoctrine()->getRepository(Categorie::class)->find($request->toArray()['categorie']['id']);
            $plat->setCategorie($category);

            $errors = $validator->validate($plat);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($plat);
            $em->flush();

        return $this->json($plat, 201, [], ['groups' => 'plat:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/api/plat/{id}", name="api_plat_delete", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        // Suppression d'un plat

        $platId = $request->get('id');

        $plat = $this->getDoctrine()->getRepository(Plat::class)->find($platId);

        if(!$plat) {
            throw new NotFoundHttpException('Plat not found');
        }

        $this->getDoctrine()->getManager()->remove($plat);
        $this->getDoctrine()->getManager()->flush();

        $data = [
            'status' => 200,
            'message' => 'Le plat a bien été supprimé'
        ];
        return new JsonResponse($data);
    }

    /**
     * @Route("/api/plat/{id}", name="api_get_plat", methods={"GET"})
     */
    public function getPlat(PlatRepository $platRepository, $id)
    {
        // Récupération d'un plat
        return $this->json($platRepository->find($id), 200, [], ['groups' => 'plat:read']);
    }

    /**
     * @Route("/api/plat/search/{criteria}", name="api_plat_search", methods={"GET"})
     */
    public function search(PlatRepository $platRepository, $criteria)
    {
        // Recherche d'un ou plusieurs plats
        return $this->json($platRepository->searchPlat(array("libelle" => $criteria)), 200, [], ['groups' => 'plat:read']);
    }

    /**
     * @Route("/api/plat/filter/{criteria}", name="api_plat_filter", methods={"GET"})
     */
    public function filter(PlatRepository $platRepository, $criteria)
    {
        // Filtrage des plats affichés
        $filtersValue = ['végétarien',
                        'vegan',
                        'pescetarien',
                        'soja',
                        'poisson',
                        'fruits à coques',
                        'gluten',
                        'mollusques',
                        'céléri',
                        'crustacés',
                        'oeuf',
                        'arachide',
                        'lupin',
                        'moutarde',
                        'produits laitiers'];
        $filters = ['vegetarien' => null,
                    'vegan' => null,
                    'pescetarien' => null,
                    'soja' => null,
                    'poisson' => null,
                    'fruits_coques' => null,
                    'gluten' => null,
                    'mollusques' => null,
                    'celeri' => null,
                    'crustaces' => null,
                    'oeuf' => null,
                    'arachide' => null, 
                    'lupin' => null,
                    'moutarde' => null,
                    'produits_laitiers' => null];
        $i = 0;
        foreach ($filters as $key => $value) {
            if ($criteria[$i] == "1") {
                $filters[$key] = $filtersValue[$i];
            }
            $i++;
        }
        return $this->json($platRepository->filterPlat($filters), 200, [], ['groups' => 'plat:read']);
    }

    /**
     * @Route("/api/plat/{id}", name="api_plat_update", methods={"PUT"})
     */
    public function update(Request $request, SerializerInterface $serializer, Plat $plat, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        // Modification d'un plat
        $platUpdate = $entityManager->getRepository(Plat::class)->find($plat->getId());
        $categoryUpdate = $this->getDoctrine()->getRepository(Categorie::class)->find($request->toArray()['categorie']['id']);
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value){
            if($key && !empty($value) && $key!="categorie" && $key!="id") {
                $name = ucfirst($key);
                $setter = 'set'.$name;
                $platUpdate->$setter($value);
            }
        }
        $platUpdate->setCategorie($categoryUpdate);
        $errors = $validator->validate($platUpdate);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'Le plat a bien été mis à jour'
        ];
        return new JsonResponse($data);
    }
}
