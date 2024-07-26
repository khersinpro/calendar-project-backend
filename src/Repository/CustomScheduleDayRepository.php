<?php

namespace App\Repository;

use App\Entity\CustomScheduleDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<CustomScheduleDay>
 */
class CustomScheduleDayRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
    )
    {
        parent::__construct($registry, CustomScheduleDay::class);
    }

    public function findAllPaginated(int $page = 1, int $limit = 10): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('c')
            ->select('c')
            ->leftJoin('c.custom_working_hours', 'cwh')
            ->addSelect('cwh')
            ->orderBy('c.id', 'ASC'),
            $page,
            $limit
        );
    }
}
