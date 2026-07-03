<?php
namespace App\Controller\Api;

use App\Service\HabitacionService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Exception\BusinessException;

#[Route('/api/habitacion')]
#[OA\Tag(name: 'Habitacion')]
class HabitacionController extends AbstractController
{
    public function __construct(private HabitacionService $service)
    {
    }

    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/habitacion',
        summary: 'Listar habitaciones',
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function list(): JsonResponse
    {
        $items = $this->service->list();

        return $this->json($items);
    }

    #[Route('/disponible', methods: ['GET'])]
    #[OA\Get(
        path: '/api/habitacion/disponible',
        summary: 'Buscar habitaciones disponibles',
        parameters: [
            new OA\Parameter(name: 'hotelId', in: 'query', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'tipoHabitacionId', in: 'query', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'acomodacionId', in: 'query', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(type: 'string'))),
            new OA\Response(response: 400, description: 'Bad Request'),
            new OA\Response(response: 404, description: 'Not Found')
        ]
    )]
    public function available(Request $request): JsonResponse
    {
        $hotelId = (int)$request->query->get('hotelId');
        $tipoHabitacionId = (int)$request->query->get('tipoHabitacionId');
        $acomodacionId = (int)$request->query->get('acomodacionId');

        $result = $this->service->buscarDisponible($hotelId, $tipoHabitacionId, $acomodacionId);

        return $this->json($result);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/habitacion',
        summary: 'Crear habitación',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'codigo', type: 'string', example: 'HAB-001'),
                    new OA\Property(property: 'hotelId', type: 'integer', example: 1),
                    new OA\Property(property: 'tipoHabitacionId', type: 'integer', example: 1),
                    new OA\Property(property: 'acomodacionId', type: 'integer', example: 1)

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
        path: '/api/habitacion/{id}',
        summary: 'Editar habitación',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'codigo', type: 'string', example: 'HAB-001'),
                    new OA\Property(property: 'hotelId', type: 'integer', example: 1),
                    new OA\Property(property: 'tipoHabitacionId', type: 'integer', example: 1),
                    new OA\Property(property: 'acomodacionId', type: 'integer', example: 1),
                    new OA\Property(property: 'libre', type: 'boolean', example: true),
                    new OA\Property(property: 'activo', type: 'boolean', example: true)
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

