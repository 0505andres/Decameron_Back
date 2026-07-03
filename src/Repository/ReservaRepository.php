<?php

namespace App\Repository;

use App\Entity\Reserva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ReservaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserva::class);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findForList(): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select([
                'r.id AS id',
                'r.cliente AS cliente',
                'r.numeroDocumento AS numeroDocumento',
                'r.edad AS edad',
                'r.fechaReserva AS fechaReserva',
                'r.fechaIngreso AS fechaIngreso',
                'r.fechaSalida AS fechaSalida',
                'hotel.id AS hotelId',
                'hotel.nombre AS hotelNombre',
                'habitacion.id AS habitacionId',
                'habitacion.codigo AS habitacionCodigo',
                'tipoHabitacion.id AS tipoHabitacionId',
                'tipoHabitacion.nombre AS tipoHabitacionNombre',
            ])
            ->leftJoin('r.hotel', 'hotel')
            ->leftJoin('r.habitacion', 'habitacion')
            ->leftJoin('habitacion.tipoHabitacion', 'tipoHabitacion')
            ->orderBy('r.id', 'ASC');

        return $qb->getQuery()->getArrayResult();
    }
}
