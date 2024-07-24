<?php

namespace App\Repository;

use App\Entity\ScheduleDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<ScheduleDay>
 */
class ScheduleDayRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
    )
    {
        parent::__construct($registry, ScheduleDay::class);
    }

    public function findAllPaginated(int $page = 1, int $limit = 10): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('s')
            ->select('s')
            ->orderBy('s.id', 'ASC'),
            $page,
            $limit
        );
    }
}
