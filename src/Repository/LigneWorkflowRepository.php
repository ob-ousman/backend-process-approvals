<?php

namespace App\Repository;

use App\Entity\LigneWorkflow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LigneWorkflow>
 *
 * @method LigneWorkflow|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneWorkflow|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneWorkflow[]    findAll()
 * @method LigneWorkflow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneWorkflowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneWorkflow::class);
    }

    //    /**
    //     * @return LigneWorkflow[] Returns an array of LigneWorkflow objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LigneWorkflow
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
