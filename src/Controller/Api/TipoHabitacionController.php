<?php

namespace App\Controller\Api;

use App\Entity\TipoHabitacion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tipo-habitacion')]
class TipoHabitacionController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $items = $em->getRepository(TipoHabitacion::class)->findAll();

        return $this->json($items);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entity = new TipoHabitacion();
        $entity->setNombre($data['nombre'] ?? '');

        $em->persist($entity);
        $em->flush();

        return $this->json($entity, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(TipoHabitacion::class);
        $entity = $repo->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['nombre'])) {
            $entity->setNombre($data['nombre']);
        }

        $em->flush();

        return $this->json($entity);
    }
}
