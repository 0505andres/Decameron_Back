<?php

namespace App\Service;

use App\Dto\TipoAcomodacionDto;
use App\Entity\TipoAcomodacion;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

final class TipoAcomodacionService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function list(): array
    {
        return $this->em->getRepository(TipoAcomodacion::class)->findAll();
    }

    public function create(array $data): TipoAcomodacion
    {
        $dto = TipoAcomodacionDto::fromArray($data);
        $entity = new TipoAcomodacion();
        $entity->setNombre($dto->nombre ?? '');

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function edit(int $id, array $data): TipoAcomodacion
    {
        $repo = $this->em->getRepository(TipoAcomodacion::class);
        $entity = $repo->find($id);
        if (!$entity) {
            throw new BusinessException('TipoAcomodacion no encontrado');
        }

        if (isset($data['nombre'])) $entity->setNombre($data['nombre']);

        $this->em->flush();

        return $entity;
    }
}
