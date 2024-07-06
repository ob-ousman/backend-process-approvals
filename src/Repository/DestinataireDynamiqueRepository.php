<?php

namespace App\Repository;

use App\Entity\DestinataireDynamique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DestinataireDynamique>
 *
 * @method DestinataireDynamique|null find($id, $lockMode = null, $lockVersion = null)
 * @method DestinataireDynamique|null findOneBy(array $criteria, array $orderBy = null)
 * @method DestinataireDynamique[]    findAll()
 * @method DestinataireDynamique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DestinataireDynamiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DestinataireDynamique::class);
    }

//    /**
//     * @return DestinataireDynamique[] Returns an array of DestinataireDynamique objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DestinataireDynamique
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
