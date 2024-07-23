<?php

namespace App\Repository;

use App\Entity\OrganizationUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<OrganizationUser>
 */
class OrganizationUserRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,  
        private PaginatorInterface $paginator
    )
    {
        parent::__construct($registry, OrganizationUser::class);
    }

    public function findAllPaginated(int $page = 1, int $limit = 10): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('o')
            ->select('o')
            ->orderBy('o.id', 'ASC'),
            $page,
            $limit,
            [
                'distinct' => false,
            ]
        );
    }
}
