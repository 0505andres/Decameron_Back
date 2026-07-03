<?php

namespace App\Controller\Api;

use App\Entity\TipoHabitacion;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tipo-habitacion')]
#[OA\Tag(name: 'TipoHabitacion')]
class TipoHabitacionController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/tipo-habitacion',
        summary: 'Listar tipos de habitación',
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $items = $em->getRepository(TipoHabitacion::class)->findAll();

        return $this->json($items);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/tipo-habitacion',
        summary: 'Crear tipo de habitación',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'nombre', type: 'string', example: 'Suite')
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Created')]
    )]
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
    #[OA\Put(
        path: '/api/tipo-habitacion/{id}',
        summary: 'Editar tipo de habitación',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'nombre', type: 'string', example: 'Suite')
                ]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
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
