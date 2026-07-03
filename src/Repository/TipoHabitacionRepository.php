<?php

namespace App\Repository;

use App\Entity\TipoHabitacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TipoHabitacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoHabitacion::class);
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
