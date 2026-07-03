<?php

namespace App\Service;

use App\Dto\HotelDto;
use App\Entity\Hotel;
use App\Entity\Ciudad;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

final class HotelService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function list(): array
    {
        $repo = $this->em->getRepository(Hotel::class);

        return $repo->findForList();
    }

    public function obtenerReporteHabitaciones(): array
    {
        $repo = $this->em->getRepository(Hotel::class);

        return $repo->findHotelsWithRoomCounts();
    }

    public function create(array $data): Hotel
    {
        $dto = HotelDto::fromArray($data);
        if (empty($dto->nombre) || empty($dto->direccion) || empty($dto->nit) || null === $dto->numeroHabitaciones) {
            throw new BusinessException('Campos requeridos faltantes');
        }

        $ciudad = null;
        if ($dto->ciudadId) {
            $ciudad = $this->em->getRepository(Ciudad::class)->find($dto->ciudadId);
            if (!$ciudad) throw new BusinessException('Ciudad no encontrada');
        }

        $existe = $this->em->getRepository(Hotel::class)->findBy(['nombre' => $dto->nombre,'nit' => $dto->nit]);
        if ($existe) throw new BusinessException('Hotel ya se encuentra registrado');

        $hotel = new Hotel();
        $hotel->setNombre($dto->nombre);
        $hotel->setDireccion($dto->direccion);
        $hotel->setNit($dto->nit);
        $hotel->setNumeroHabitaciones((int)$dto->numeroHabitaciones);
        $hotel->setCiudad($ciudad);
        if (null !== $dto->activo) $hotel->setActivo($dto->activo);

        $this->em->persist($hotel);
        $this->em->flush();

        return $hotel;
    }

    public function edit(int $id, array $data): Hotel
    {
        $repo = $this->em->getRepository(Hotel::class);
        $hotel = $repo->find($id);
        if (!$hotel) throw new BusinessException('Not found');
        if($data['nombre'] != $hotel->getNombre() || $data['nit'] != $hotel->getNit()) {
            $existe = $this->em->getRepository(Hotel::class)->findBy(['nombre' => $data['nombre'],'nit' => $data['nit']]);
            if ($existe) throw new BusinessException('Hotel ya se encuentra registrado');
        }

        if (isset($data['nombre'])) $hotel->setNombre($data['nombre']);
        if (isset($data['direccion'])) $hotel->setDireccion($data['direccion']);
        if (isset($data['nit'])) $hotel->setNit($data['nit']);
        if (isset($data['numeroHabitaciones'])) $hotel->setNumeroHabitaciones((int)$data['numeroHabitaciones']);
        if (isset($data['ciudadId'])) {
            $ciudad = $this->em->getRepository(Ciudad::class)->find($data['ciudadId']);
            if (!$ciudad) throw new BusinessException('Ciudad no encontrada');
            $hotel->setCiudad($ciudad);
        }
        if (isset($data['activo'])) $hotel->setActivo((bool)$data['activo']);

        $this->em->flush();

        return $hotel;
    }
}
