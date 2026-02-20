<?php

namespace App\Repository;

use App\Entity\Racquet;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Racquet>
 *
 * @method Racquet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Racquet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Racquet[]    findAll()
 * @method Racquet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RacquetRepository extends ServiceEntityRepository
{

    public const PAGINATOR_PER_PAGE = 9;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Racquet::class);
    }

    public function add(Racquet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Racquet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Racquet[] Returns an array of Racquet objects
     */
    public function getRacquetPaginator(int $offset, ?QueryBuilder $racquetsQb = null)
    {
        $queryBuilder = ($racquetsQb ?: $this->createQueryBuilder('r'));

        $queryBuilder->orderBy('r.id', 'ASC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset);

        return new Paginator($queryBuilder->getQuery());
    }

    public function findWithSearch(SearchData $searchData)
    {
        $racquets = $this->createQueryBuilder('r');

        if ($searchData->query !== null) {
            $racquets
                ->andWhere('r.brand LIKE :query')
                ->orWhere('r.model LIKE :query')
                ->setParameter('query', "%{$searchData->query}%");
        }

        if ($searchData->quantity !== null) {
            $this->applyStockFilter($racquets, $searchData->quantity);
        }

        if ($searchData->rating !== null) {
            $this->applyRatingFilter($racquets, $searchData->rating);
        }

        $offset = max(0, ($searchData->page - 1) * self::PAGINATOR_PER_PAGE);

        return $this->getRacquetPaginator($offset, $racquets);
    }

    private function applyStockFilter($racquets, int $data)
    {
        switch ($data) {
            case 1:
                $min = 0;
                $max = 10;
                break;

            case 2:
                $min = 10;
                $max = 30;
                break;

            case 3:
                $min = 30;
                $max = 60;
                break;

            case 4:
                $min = 60;
                $max = null;
                break;
        }

        $racquets->andWhere('r.quantity >= :quantity_min')
            ->setParameter('quantity_min', $min);

        if ($max) {
            $racquets->andWhere('r.quantity <= :quantity_max')
                ->setParameter('quantity_max', $max);
        }

        return $racquets;    
    }

    private function applyRatingFilter($racquets, int $data)
    {
        switch ($data) {
            case 1:
                $min = 0;
                $max = 2;
                break;

            case 2:
                $min = 3;
                $max = 6;
                break;

            case 3:
                $min = 7;
                $max = 10;
                break;
        }

        $racquets->andWhere('r.avgRating >= :avgRating_min')
            ->setParameter('avgRating_min', $min)
            ->andWhere('r.avgRating <= :avgRating_max')
            ->setParameter('avgRating_max', $max)
        ;

        return $racquets;
    }
}
