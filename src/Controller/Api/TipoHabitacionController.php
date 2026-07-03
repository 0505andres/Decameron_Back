<?php

namespace App\Controller\Api;

use App\Service\TipoHabitacionService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tipo-habitacion')]
#[OA\Tag(name: 'TipoHabitacion')]
class TipoHabitacionController extends AbstractController
{
    public function __construct(private TipoHabitacionService $service)
    {
    }

    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/tipo-habitacion',
        summary: 'Listar tipos de habitación',
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function list(): JsonResponse
    {
        $items = $this->service->list();

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
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entity = $this->service->create($data);

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
    public function edit(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entity = $this->service->edit($id, $data);

        return $this->json($entity);
    }
}
