<?php

namespace App\Repository;

use App\Entity\Racquet;
use App\Model\FilterData;
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

    public function findByBrandAndModel(SearchData $searchData)
    {
        $racquets = $this->createQueryBuilder('r');

        if ($searchData->query !== null) {
            $racquets
                ->andWhere('r.brand LIKE :query')
                ->orWhere('r.model LIKE :query')
                ->setParameter('query', "%{$searchData->query}%");
        }

        $offset = max(0, ($searchData->page - 1) * self::PAGINATOR_PER_PAGE);

        return $this->getRacquetPaginator($offset, $racquets);
    }

    public function findBySpecs(FilterData $filterData)
    {
        $racquets = $this->createQueryBuilder('r');

        if ($filterData->weight !== null) {
            $racquets
                ->andWhere('r.weight = :weight')
                ->setParameter('weight', $filterData->weight);
        }

        if ($filterData->head_size !== null) {
            $racquets
                ->andWhere('r.head_size = :head_size')
                ->setParameter('head_size', $filterData->head_size);
        }

        if ($filterData->string_pattern !== null) {
            $racquets
                ->andWhere('r.string_pattern = :string_pattern')
                ->setParameter('string_pattern', $filterData->string_pattern);
        }

        if ($filterData->grip_size !== null) {
            $racquets
                ->andWhere('r.grip_size = :grip_size')
                ->setParameter('grip_size', $filterData->grip_size);
        }

        $offset = max(0, ($filterData->page - 1) * self::PAGINATOR_PER_PAGE);

        return $this->getRacquetPaginator($offset, $racquets);
    }

    private function handleArray(array $array): array {
        array_unique($array, SORT_REGULAR);
        sort($array, SORT_REGULAR);

        return $array;
    }

    public function getAllUniquesWeights()
    {
        $racquets = $this->findAll();
        $weights = [];
        foreach ($racquets as $racquet) {
            array_push($weights, $racquet->getWeight());
        }

        return $this->handleArray($weights);
    }

    public function getAllUniquesHeadSizes()
    {
        $racquets = $this->findAll();
        $headSizes = [];
        foreach ($racquets as $racquet) {
            array_push($headSizes, $racquet->getHeadSize());
        }

        return $this->handleArray($headSizes);
    }

    public function getAllUniquesGripSizes()
    {
        $racquets = $this->findAll();
        $gripSizes = [];
        foreach ($racquets as $racquet) {
            array_push($gripSizes, $racquet->getGripSize());
        }

        return $this->handleArray($gripSizes);
    }

    public function getAllUniquesStringPatterns()
    {
        $racquets = $this->findAll();
        $stringPatterns = [];
        foreach ($racquets as $racquet) {
            array_push($stringPatterns, $racquet->getStringPattern());
        }

        return $this->handleArray($stringPatterns);
    }
}
