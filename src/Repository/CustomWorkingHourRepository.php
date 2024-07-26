<?php

namespace App\Repository;

use App\Entity\CustomWorkingHour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<CustomWorkingHour>
 */
class CustomWorkingHourRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
    )
    {
        parent::__construct($registry, CustomWorkingHour::class);
    }

    public function findAllPaginated(int $page = 1, int $limit = 10): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('c')
            ->select('c')
            ->orderBy('c.id', 'ASC'),
            $page,
            $limit
        );
    }
}
