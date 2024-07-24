<?php

namespace App\Repository;

use App\Entity\EventType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<EventType>
 */
class EventTypeRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
    )
    {
        parent::__construct($registry, EventType::class);
    }

    public function findAllPaginated(int $page = 1, int $limit = 10): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.id', 'ASC'),
            $page,
            $limit,
            [
                'distinct' => false,
            ]
        );
    }
}
