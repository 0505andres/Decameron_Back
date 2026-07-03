<?php

namespace App\Service;

use App\Dto\ReservaDto;
use App\Entity\Reserva;
use App\Entity\Hotel;
use App\Entity\Habitacion;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

final class ReservaService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function list(): array
    {
        $repo = $this->em->getRepository(Reserva::class);
        return $repo->findForList();

    }

    public function create(array $data): Reserva
    {
        $dto = ReservaDto::fromArray($data);

        foreach (['cliente','numeroDocumento','edad','fechaReserva','fechaIngreso','fechaSalida','hotelId','habitacionId'] as $f) {
            if (empty($data[$f]) && $data[$f] !== 0) {
                throw new BusinessException('Campo requerido faltante: ' . $f);
            }
        }

        $hotel = $this->em->getRepository(Hotel::class)->find($dto->hotelId);
        if (!$hotel) throw new BusinessException('Hotel no encontrado');

        $habitacion = $this->em->getRepository(Habitacion::class)->find($dto->habitacionId);
        if (!$habitacion) throw new BusinessException('Habitacion no encontrada');

        if($habitacion->getActivo() === false) {
            throw new BusinessException('La habitación no está activa');
        }
        if($habitacion->getLibre() === false) {
            throw new BusinessException('La habitación no está libre');
        }


        $res = new Reserva();
        $res->setCliente($dto->cliente);
        $res->setNumeroDocumento($dto->numeroDocumento);
        $res->setEdad((int)$dto->edad);
        $res->setFechaReserva(new \DateTime($dto->fechaReserva));
        $res->setFechaIngreso(new \DateTime($dto->fechaIngreso));
        $res->setFechaSalida(new \DateTime($dto->fechaSalida));
        $res->setHotel($hotel);
        $res->setHabitacion($habitacion);
        $habitacion->setLibre(false);

        $this->em->persist($res);
        $this->em->persist($habitacion);
        $this->em->flush();

        return $res;
    }

    public function edit(int $id, array $data): Reserva
    {
        $repo = $this->em->getRepository(Reserva::class);
        $res = $repo->find($id);
        if (!$res) throw new BusinessException('Not found');

        if (isset($data['cliente'])) $res->setCliente($data['cliente']);
        if (isset($data['numeroDocumento'])) $res->setNumeroDocumento($data['numeroDocumento']);
        if (isset($data['edad'])) $res->setEdad((int)$data['edad']);
        if (isset($data['fechaReserva'])) $res->setFechaReserva(new \DateTime($data['fechaReserva']));
        if (isset($data['fechaIngreso'])) $res->setFechaIngreso(new \DateTime($data['fechaIngreso']));
        if (isset($data['fechaSalida'])) $res->setFechaSalida(new \DateTime($data['fechaSalida']));

        if (isset($data['hotelId'])) {
            $hotel = $this->em->getRepository(Hotel::class)->find($data['hotelId']);
            if (!$hotel) throw new BusinessException('Hotel no encontrado');
            $res->setHotel($hotel);
        }

        if (isset($data['habitacionId'])) {
            $hab = $this->em->getRepository(Habitacion::class)->find($data['habitacionId']);
            if (!$hab) throw new BusinessException('Habitacion no encontrada');
            $res->setHabitacion($hab);
        }

        $this->em->flush();

        return $res;
    }
}
