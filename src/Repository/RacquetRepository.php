<?php

namespace App\Repository;

use App\Entity\Racquet;
use App\Model\FilterData;
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

    public function findWithFilter(FilterData $filterData)
    {
        $racquets = $this->createQueryBuilder('r');

        if ($filterData->quantity !== null) {
            $this->applyStockFilter($racquets, $filterData->quantity);
        }

        if ($filterData->rating !== null) {
            $this->applyRatingFilter($racquets, $filterData->rating);
        }

        $offset = max(0, ($filterData->page - 1) * self::PAGINATOR_PER_PAGE);

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

    public function findByFilterTerm(string $filterTerm): array
    {
        $qb = $this->createQueryBuilder('r');
        
        if (!empty($filterTerm)) {
            $qb->where('r.brand LIKE :term OR r.model LIKE :term')
                ->setParameter('term', '%' . $filterTerm . '%');
        }
        
        $racquets = $qb->getQuery()->getResult();
        $dataRacquet = [];

        foreach($racquets as $racquet){
            $dataRacquet[] = [
                "id" => $racquet->getId(),
                "brand" => $racquet->getBrand(),
                "model" => $racquet->getModel(),
                "price" => $racquet->getPrice(),
                "quantity" => $racquet->getQuantity(),
                "rating" => $racquet->getAvgRating(),
            ];           
        }

        return $dataRacquet;
    }
    
}
