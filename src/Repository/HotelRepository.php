<?php

namespace App\Repository;

use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    /**
     * Devuelve lista de hoteles como array asociativo listo para consumo (array list)
     *
     * @return array<int, array<string, mixed>>
     */
    public function findForList(): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select(
                'h.id AS id',
                'h.nombre AS nombre',
                'h.direccion AS direccion',
                'h.nit AS nit',
                'h.numeroHabitaciones AS numeroHabitaciones',
                'c.id AS ciudadId',
                'h.activo AS activo'
            )
            ->leftJoin('h.ciudad', 'c')
            ->orderBy('h.id', 'ASC');

        return $qb->getQuery()->getArrayResult();
    }
}
