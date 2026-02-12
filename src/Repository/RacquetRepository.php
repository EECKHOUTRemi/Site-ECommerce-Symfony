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
    public function getRacquetPaginator(int $offset, ?QueryBuilder $racquets = null)
    {
        $queryBuilder = ($racquets ?: $this->createQueryBuilder('r'));

        $queryBuilder->orderBy('r.id', 'ASC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset);

        return new Paginator($queryBuilder->getQuery());
    }

    public function findBySearch(SearchData $searchData){
        $racquets = $this->createQueryBuilder('r');

        if (!empty($searchData->query)) {
            $racquets->andWhere('r.brand LIKE :query')
                ->setParameter('query', "%{$searchData->query}%");
        }

        $offset = max(0, ($searchData->page - 1) * self::PAGINATOR_PER_PAGE);

        return $this->getRacquetPaginator($offset, $racquets);
    }
}
