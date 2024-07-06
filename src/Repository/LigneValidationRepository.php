<?php

namespace App\Repository;

use App\Entity\LigneValidation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LigneValidation>
 *
 * @method LigneValidation|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneValidation|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneValidation[]    findAll()
 * @method LigneValidation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneValidationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneValidation::class);
    }

    //    /**
    //     * @return LigneValidation[] Returns an array of LigneValidation objects
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

    //    public function findOneBySomeField($value): ?LigneValidation
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
