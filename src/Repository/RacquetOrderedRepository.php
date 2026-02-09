<?php

namespace App\Repository;

use App\Entity\RacquetOrdered;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RacquetOrdered>
 *
 * @method RacquetOrdered|null find($id, $lockMode = null, $lockVersion = null)
 * @method RacquetOrdered|null findOneBy(array $criteria, array $orderBy = null)
 * @method RacquetOrdered[]    findAll()
 * @method RacquetOrdered[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RacquetOrderedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RacquetOrdered::class);
    }

    public function add(RacquetOrdered $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RacquetOrdered $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RacquetOrdered[] Returns an array of RacquetOrdered objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RacquetOrdered
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
