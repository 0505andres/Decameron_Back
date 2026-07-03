<?php

namespace App\Repository;

use App\Entity\Ciudad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class CiudadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ciudad::class);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findForList(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->select([
                'c.id AS id',
                'c.nombre AS nombre',
            ])
            ->orderBy('c.id', 'ASC');

        return $qb->getQuery()->getArrayResult();
    }
}
