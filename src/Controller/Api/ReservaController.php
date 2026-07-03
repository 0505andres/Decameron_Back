<?php

namespace App\Controller\Api;

use App\Entity\Reserva;
use App\Service\ReservaService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/reserva')]
#[OA\Tag(name: 'Reserva')]
class ReservaController extends AbstractController
{
    public function __construct(private ReservaService $service)
    {
    }

    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/reserva',
        summary: 'Listar reservas',
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function list(): JsonResponse
    {
        $items = $this->service->list();

        return $this->json($items);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/reserva',
        summary: 'Crear reserva',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'cliente', type: 'string', example: 'Juan'),
                    new OA\Property(property: 'numeroDocumento', type: 'string', example: '12345'),
                    new OA\Property(property: 'edad', type: 'integer', example: 30),
                    new OA\Property(property: 'fechaReserva', type: 'string', format: 'date-time', example: '2024-01-01T00:00:00Z'),
                    new OA\Property(property: 'fechaIngreso', type: 'string', format: 'date-time', example: '2024-01-02T00:00:00Z'),
                    new OA\Property(property: 'fechaSalida', type: 'string', format: 'date-time', example: '2024-01-05T00:00:00Z'),
                    new OA\Property(property: 'hotelId', type: 'integer', example: 1),
                    new OA\Property(property: 'habitacionId', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Created')]
    )]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entity = $this->service->create($data);

        return $this->json($entity, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/reserva/{id}',
        summary: 'Editar reserva',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'cliente', type: 'string', example: 'Juan Pérez'),
                    new OA\Property(property: 'numeroDocumento', type: 'string', example: '12345678'),
                    new OA\Property(property: 'edad', type: 'integer', example: 35),
                    new OA\Property(property: 'fechaReserva', type: 'string', format: 'date', example: '2026-07-10'),
                    new OA\Property(property: 'fechaIngreso', type: 'string', format: 'date', example: '2026-07-15'),
                    new OA\Property(property: 'fechaSalida', type: 'string', format: 'date', example: '2026-07-20'),
                    new OA\Property(property: 'hotelId', type: 'integer', example: 1),
                    new OA\Property(property: 'habitacionId', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function edit(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entity = $this->service->edit($id, $data);

        return $this->json($entity);
    }
}
