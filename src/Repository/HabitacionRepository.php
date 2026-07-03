<?php

namespace App\Repository;

use App\Entity\Habitacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class HabitacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habitacion::class);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findForList(): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select([
                'h.id AS id',
                'h.codigo AS codigo',
                'h.libre AS libre',
                'h.activo AS activo',
                'hotel.id AS hotelId',
                'hotel.nombre AS hotelNombre',
                'tipo.id AS tipoHabitacionId',
                'tipo.nombre AS tipoHabitacionNombre',
                'acom.id AS acomodacionId',
                'acom.nombre AS acomodacionNombre',
            ])
            ->leftJoin('h.hotel', 'hotel')
            ->leftJoin('h.tipoHabitacion', 'tipo')
            ->leftJoin('h.acomodacion', 'acom')
            ->orderBy('h.id', 'ASC');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Busca habitaciones disponibles por hotel, tipo de acomodación y tipo de habitación.
     *
     * @return string[]
     */
    public function findAvailableCodes(int $hotelId, int $tipoHabitacionId, int $acomodacionId): array
    {
        $qb = $this->createQueryBuilder('h')
            ->select('h.id as id','h.codigo AS codigo')
            ->leftJoin('h.hotel', 'hotel')
            ->leftJoin('h.tipoHabitacion', 'tipo')
            ->leftJoin('h.acomodacion', 'acom')
            ->where('hotel.id = :hotelId')
            ->andWhere('tipo.id = :tipoHabitacionId')
            ->andWhere('acom.id = :acomodacionId')
            ->andWhere('h.libre = true')
            ->andWhere('h.activo = true')
            ->setParameter('hotelId', $hotelId)
            ->setParameter('tipoHabitacionId', $tipoHabitacionId)
            ->setParameter('acomodacionId', $acomodacionId)
            ->orderBy('h.codigo', 'ASC');

       return $qb->getQuery()->getArrayResult();
    }
}
