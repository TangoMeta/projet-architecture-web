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
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ApiPlatController extends AbstractController
{
    /**
     * @Route("/api/plat", name="api_plat_index", methods={"GET"})
     */
    public function index(PlatRepository $platRepository)
    {
        return $this->json($platRepository->findAll(), 200, [], ['groups' => 'plat:read']);
    }

    /**
     * @Route("/api/plat", name="api_plat_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em,
    ValidatorInterface $validator) : Response
    {
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
    public function delete(Plat $plat)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($plat);
        $em->flush();

        return $this->json($plat, 200);
    }

    /**
     * @Route("/api/plat/{id}", name="api_plat_update", methods={"PUT"})
     */
    public function update(Request $request, SerializerInterface $serializer, Plat $plat, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $platUpdate = $entityManager->getRepository(Plat::class)->find($plat->getId());
        $categoryUpdate = $this->getDoctrine()->getRepository(Categorie::class)->find($request->toArray()['categorie']['id']);
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value){
            if($key && !empty($value) && $key!="categorie") {
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
