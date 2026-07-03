<?php

namespace App\Repository;

use App\Entity\TipoAcomodacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TipoAcomodacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoAcomodacion::class);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findForList(): array
    {
        $qb = $this->createQueryBuilder('t')
            ->select([
                't.id AS id',
                't.nombre AS nombre',
            ])
            ->orderBy('t.id', 'ASC');

        return $qb->getQuery()->getArrayResult();
    }
}
