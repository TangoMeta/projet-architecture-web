<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
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

header("Access-Control-Allow-Origin: *");

class ApiCategoryController extends AbstractController
{
    /**
     * @Route("/api/category", name="api_category_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository)
    {
        // Récupération des catégories
        return $this->json($categorieRepository->findAll(), 200, [], ['groups' => 'category:read']);
    }

    /**
     * @Route("/api/category", name="api_category_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em,
    ValidatorInterface $validator)
    {
        // Création d'une catégorie

        $jsonRecu = $request->getContent();

        try {
            $categorie = $serializer->deserialize($jsonRecu, Categorie::class, 'json');

            $errors = $validator->validate($categorie);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($categorie);
            $em->flush();

        return $this->json($categorie, 201, [], ['groups' => 'category:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
        
    }

    /**
     * @Route("/api/category/{id}", name="api_category_delete", methods={"DELETE"})
     */
    public function delete(Categorie $categorie)
    {
        // Suppression d'une catégorie

        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return $this->json($categorie, 200);
    }

    /**
     * @Route("/api/category/{id}", name="api_get_category", methods={"GET"})
     */
    public function getPlat(CategorieRepository $categorieRepository, $id)
    {
        // Récupération d'une catégorie
        return $this->json($categorieRepository->find($id), 200, [], ['groups' => 'category:read']);
    }

    /**
     * @Route("/api/category/{id}", name="api_category_update", methods={"PUT"})
     */
    public function update(Request $request, SerializerInterface $serializer, Categorie $categorie, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        // Modificiation d'une catégorie
        
        $categoryUpdate = $entityManager->getRepository(Categorie::class)->find($categorie->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value){
            if($key && !empty($value) && $key!="id") {
                $name = ucfirst($key);
                $setter = 'set'.$name;
                $categoryUpdate->$setter($value);
            }
        }
        $errors = $validator->validate($categoryUpdate);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'La catégorie a bien été mise à jour'
        ];
        return new JsonResponse($data);
    }
}
