<?php

namespace App\Service;

use App\Dto\TipoHabitacionDto;
use App\Entity\TipoHabitacion;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

final class TipoHabitacionService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function list(): array
    {
        return $this->em->getRepository(TipoHabitacion::class)->findAll();
    }

    public function create(array $data): TipoHabitacion
    {
        $dto = TipoHabitacionDto::fromArray($data);
        $entity = new TipoHabitacion();
        $entity->setNombre($dto->nombre ?? '');

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function edit(int $id, array $data): TipoHabitacion
    {
        $repo = $this->em->getRepository(TipoHabitacion::class);
        $entity = $repo->find($id);
        if (!$entity) {
            throw new BusinessException('TipoHabitacion no encontrado');
        }

        if (isset($data['nombre'])) $entity->setNombre($data['nombre']);

        $this->em->flush();

        return $entity;
    }
}
