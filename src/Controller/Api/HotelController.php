<?php

namespace App\Controller\Api;

use App\Entity\Hotel;
use App\Entity\Ciudad;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\HotelService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/hotel')]
#[OA\Tag(name: 'Hotel')]
class HotelController extends AbstractController
{
    public function __construct(private HotelService $service)
    {
    }

    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/hotel',
        summary: 'Listar hoteles',
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function list(): JsonResponse
    {
        $items = $this->service->list();

        return $this->json($items);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/hotel',
        summary: 'Crear hotel',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'nombre', type: 'string', example: 'Hotel Decameron'),
                    new OA\Property(property: 'ciudadId', type: 'integer', example: 1)
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
        path: '/api/hotel/{id}',
        summary: 'Editar hotel',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'nombre', type: 'string', example: 'Hotel Nuevo'),
                    new OA\Property(property: 'ciudadId', type: 'integer', example: 2)
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

