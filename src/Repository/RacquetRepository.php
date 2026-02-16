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

        if ($searchData->weight !== null) {
            $this->applyWeightFilter($racquets, $searchData->weight);
        }

        if ($searchData->head_size !== null) {
            $this->applyHeadSizeFilter($racquets, $searchData->head_size);
        }

        if ($searchData->string_pattern !== null) {
            $racquets
                ->andWhere('r.string_pattern = :string_pattern')
                ->setParameter('string_pattern', $searchData->string_pattern);
        }

        if ($searchData->grip_size !== null) {
            $racquets
                ->andWhere('r.grip_size = :grip_size')
                ->setParameter('grip_size', $searchData->grip_size);
        }

        $offset = max(0, ($searchData->page - 1) * self::PAGINATOR_PER_PAGE);

        return $this->getRacquetPaginator($offset, $racquets);
    }

    private function applyWeightFilter($racquets, int $data)
    {
        switch ($data) {
            case 1:
                $min = 160;
                $max = 270;
                break;

            case 2:
                $min = 270;
                $max = 290;
                break;

            case 3:
                $min = 290;
                $max = 310;
                break;

            case 4:
                $min = 310;
                $max = 350;
                break;
        }

        $racquets->andWhere('r.weight >= :weight_min')
            ->andWhere('r.weight <= :weight_max')
            ->setParameter('weight_min', $min)
            ->setParameter('weight_max', $max);

        return $racquets;
    }

    private function applyHeadSizeFilter($racquets, int $data)
    {
        switch ($data) {
            case 1:
                $min = 600;
                $max = 630;
                break;

            case 2:
                $min = 630;
                $max = 660;
                break;

            case 3:
                $min = 660;
                $max = 690;
                break;

            case 4:
                $min = 690;
                $max = 740;
                break;
        }

        $racquets->andWhere('r.head_size >= :head_size_min')
            ->andWhere('r.head_size <= :head_size_max')
            ->setParameter('head_size_min', $min)
            ->setParameter('head_size_max', $max);

        return $racquets;
    }

    private function handleArray(array $array): array
    {
        array_unique($array, SORT_REGULAR);
        sort($array, SORT_REGULAR);

        return $array;
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
