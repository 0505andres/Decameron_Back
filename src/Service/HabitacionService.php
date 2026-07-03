<?php

namespace App\Service;

use App\Dto\HabitacionDto;
use App\Entity\Habitacion;
use App\Entity\Hotel;
use App\Entity\TipoHabitacion;
use App\Entity\TipoAcomodacion;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

final class HabitacionService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function list(): array
    {
        return $this->em->getRepository(Habitacion::class)->findAll();
    }

    public function create(array $data): Habitacion
    {
        $dto = HabitacionDto::fromArray($data);
        if (empty($dto->codigo) || empty($dto->hotelId)) {
            throw new BusinessException('Campos requeridos faltantes');
        }

        $hotel = $this->em->getRepository(Hotel::class)->find($dto->hotelId);
        if (!$hotel) throw new BusinessException('Hotel no encontrado');

        $numHabitaciones = $hotel->getNumeroHabitaciones();
        $numHabitacionesRegistradas = $this->em->getRepository(Habitacion::class)->count(['hotel' => $hotel]);
        if ($numHabitacionesRegistradas >= $numHabitaciones) {
            throw new BusinessException('No se pueden registrar más habitaciones para este hotel. Se ha alcanzado el límite de ' . $numHabitaciones . ' habitaciones.');
        }


        $tipo = null;
        if ($dto->tipoHabitacionId) {
            $tipo = $this->em->getRepository(TipoHabitacion::class)->find($dto->tipoHabitacionId);
            if (!$tipo) throw new BusinessException('TipoHabitacion no encontrado');
        }

        $acom = null;
        if ($dto->acomodacionId) {
            $acom = $this->em->getRepository(TipoAcomodacion::class)->find($dto->acomodacionId);
            if (!$acom) throw new BusinessException('TipoAcomodacion no encontrado');
        }

        $existe = $this->em->getRepository(Habitacion::class)->findBy(['codigo' => $dto->codigo, 'hotel' => $hotel]);
        if ($existe) throw new BusinessException('Habitación ya se encuentra registrada para este hotel');

        $h = new Habitacion();
        $h->setCodigo($dto->codigo);
        $h->setHotel($hotel);
        $h->setTipoHabitacion($tipo);
        $h->setAcomodacion($acom);
        if (null !== $dto->libre) $h->setLibre($dto->libre);
        if (null !== $dto->activo) $h->setActivo($dto->activo);

        $this->em->persist($h);
        $this->em->flush();

        return $h;
    }

    public function edit(int $id, array $data): Habitacion
    {
        $repo = $this->em->getRepository(Habitacion::class);
        $h = $repo->find($id);
        if (!$h) throw new BusinessException('Not found');

        if($h->getCodigo() != $data['codigo'] || $h->getHotel()->getId() != $data['hotelId']) {
            $existe = $this->em->getRepository(Habitacion::class)->findBy(['codigo' =>$data['codigo'], 'hotel' => $data['hotelId']]);
            if ($existe) throw new BusinessException('Habitación ya se encuentra registrada para este hotel');
        }

        if (isset($data['codigo'])) $h->setCodigo($data['codigo']);
        if (isset($data['hotelId'])) {
            $hotel = $this->em->getRepository(Hotel::class)->find($data['hotelId']);
            if (!$hotel) throw new BusinessException('Hotel no encontrado');
            $h->setHotel($hotel);
        }
        if (isset($data['tipoHabitacionId'])) {
            $tipo = $this->em->getRepository(TipoHabitacion::class)->find($data['tipoHabitacionId']);
            if (!$tipo) throw new BusinessException('TipoHabitacion no encontrado');
            $h->setTipoHabitacion($tipo);
        }
        if (isset($data['acomodacionId'])) {
            $acom = $this->em->getRepository(TipoAcomodacion::class)->find($data['acomodacionId']);
            if (!$acom) throw new BusinessException('TipoAcomodacion no encontrado');
            $h->setAcomodacion($acom);
        }
        if (isset($data['libre'])) $h->setLibre((bool)$data['libre']);
        if (isset($data['activo'])) $h->setActivo((bool)$data['activo']);

        $this->em->flush();

        return $h;
    }
}
