<?php

namespace App\Repository;

use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Schedule>
 */
class ScheduleRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
    )
    {
        parent::__construct($registry, Schedule::class);
    }

    public function findAllPaginated(int $page = 1, int $limit = 10): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        
        return $this->paginator->paginate(
            $this->createQueryBuilder('s')
            ->select('s')
            ->leftJoin('s.schedule_days', 'sd')
            ->leftJoin('sd.working_hours', 'wh')
            ->addSelect('sd')
            ->addSelect('wh')
            ->orderBy('s.id', 'ASC'),
            $page,
            $limit
        );
    }
}
