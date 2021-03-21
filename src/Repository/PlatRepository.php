<?php

namespace App\Repository;

use App\Entity\Plat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Plat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plat[]    findAll()
 * @method Plat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plat::class);
    }

    public function searchPlat($criteria)
    {
        return $this->createQueryBuilder('p')
                    ->where('p.libelle LIKE :search')
                    ->setParameter('search', '%'.$criteria['libelle'].'%')
                    ->orWhere('p.ingredients LIKE :search')
                    ->setParameter('search', '%'.$criteria['libelle'].'%')
                    ->orWhere('p.allergene LIKE :search')
                    ->setParameter('search', '%'.$criteria['libelle'].'%')
                    ->orWhere('p.regime LIKE :search')
                    ->setParameter('search', '%'.$criteria['libelle'].'%')
                    ->getQuery()
                    ->getResult()
        ;
    }

    public function filterPlat($criteria)
    {
        $query = $this->createQueryBuilder('p');

        if(!is_null($criteria['vegetarien']))
        {
            $query = $query
                     ->andWhere('p.regime LIKE :vegetarien')
                     ->setParameter('vegetarien', '%'.$criteria['vegetarien'].'%');
        }

        if(!is_null($criteria['vegan']))
        {
            $query = $query
                    ->andWhere('p.regime LIKE :vegan')
                    ->setParameter('vegan', '%'.$criteria['vegan'].'%')
            ;
        }
        
        if(!is_null($criteria['pescetarien']))
        {
            $query = $query
                    ->andWhere('p.regime LIKE :pescetarien')
                    ->setParameter('pescetarien', '%'.$criteria['pescetarien'].'%')
            ;
        }
        
        if(!is_null($criteria['soja']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :soja')
                    ->setParameter('soja', '%'.$criteria['soja'].'%')
            ;
        }
        
        if(!is_null($criteria['poisson']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :poisson')
                    ->setParameter('poisson', '%'.$criteria['poisson'].'%')
            ;
        }
        
        if(!is_null($criteria['fruits_coques']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :fruits_coques')
                    ->setParameter('fruits_coques', '%'.$criteria['fruits_coques'].'%')
            ;
        }
        
        if(!is_null($criteria['gluten']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :gluten')
                    ->setParameter('gluten', '%'.$criteria['gluten'].'%')
            ;
        }
        
        if(!is_null($criteria['mollusques']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :mollusques')
                    ->setParameter('mollusques', '%'.$criteria['mollusques'].'%')
            ;
        }
        
        if(!is_null($criteria['celeri']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :celeri')
                    ->setParameter('celeri', '%'.$criteria['celeri'].'%')
            ;
        }
        
        if(!is_null($criteria['crustaces']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :crustaces')
                    ->setParameter('crustaces', '%'.$criteria['crustaces'].'%')
            ;
        }
        
        if(!is_null($criteria['oeuf']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :oeuf')
                    ->setParameter('oeuf', '%'.$criteria['oeuf'].'%')
            ;
        }
        
        if(!is_null($criteria['arachide']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :arachide')
                    ->setParameter('arachide', '%'.$criteria['arachide'].'%')
            ;
        }
        
        if(!is_null($criteria['lupin']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :lupin')
                    ->setParameter('lupin', '%'.$criteria['lupin'].'%')
            ;
        }
        
        if(!is_null($criteria['moutarde']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :moutarde')
                    ->setParameter('moutarde', '%'.$criteria['moutarde'].'%')
            ;
        }
        
        if(!is_null($criteria['produits_laitiers']))
        {
            $query = $query
                    ->andWhere('p.allergene NOT LIKE :produits_laitiers')
                    ->setParameter('produits_laitiers', '%'.$criteria['produits_laitiers'].'%')
            ;
        }
        return $query->getQuery()->getResult();
    }





    // /**
    //  * @return Plat[] Returns an array of Plat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Plat
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
