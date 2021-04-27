<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    // public function findOneById($id)
    // {
    //     return $this->createQueryBuilder('c')
    //         ->where('c.id like :id')
    //         ->setParameter('id', $id)
    //         ->getQuery()
    //         ->getResult();
    // }
}
