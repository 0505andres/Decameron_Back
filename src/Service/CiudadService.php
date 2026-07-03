<?php

namespace App\Service;

use App\Dto\CiudadDto;
use App\Entity\Ciudad;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;

final class CiudadService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function list(): array
    {
        return $this->em->getRepository(Ciudad::class)->findForList();
    }

    public function create(array $data): Ciudad
    {
        $dto = CiudadDto::fromArray($data);
        if (empty($dto->nombre)) {
            throw new BusinessException('Campo nombre es requerido');
        }

        $entity = new Ciudad();
        $entity->setNombre($dto->nombre);

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function edit(int $id, array $data): Ciudad
    {
        $repo = $this->em->getRepository(Ciudad::class);
        $entity = $repo->find($id);
        if (!$entity) {
            throw new BusinessException('Ciudad no encontrada');
        }

        if (isset($data['nombre'])) {
            $entity->setNombre($data['nombre']);
        }

        $this->em->flush();

        return $entity;
    }
}
