<?php
use App\Service\HabitacionService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
                    new OA\Property(property: 'acomodacionId', type: 'integer', example: 1),
                    new OA\Property(property: 'libre', type: 'boolean', example: true),
                    new OA\Property(property: 'activo', type: 'boolean', example: true)
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

