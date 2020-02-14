<?php

namespace App\Repository;

use App\Entity\GroupTraining;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GroupTraining|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupTraining|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupTraining[]    findAll()
 * @method GroupTraining[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupTrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupTraining::class);
    }

    // /**
    //  * @return GroupTraining[] Returns an array of GroupTraining objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupTraining
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
