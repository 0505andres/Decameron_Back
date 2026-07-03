<?php

namespace App\Controller\Api;

use App\Entity\TipoAcomodacion;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tipo-acomodacion')]
#[OA\Tag(name: 'TipoAcomodacion')]
class TipoAcomodacionController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/tipo-acomodacion',
        summary: 'Listar tipos de acomodación',
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $items = $em->getRepository(TipoAcomodacion::class)->findAll();

        return $this->json($items);
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/tipo-acomodacion',
        summary: 'Crear tipo de acomodación',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'nombre', type: 'string', example: 'Doble')
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Created')]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $entity = new TipoAcomodacion();
        $entity->setNombre($data['nombre'] ?? '');

        $em->persist($entity);
        $em->flush();

        return $this->json($entity, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/tipo-acomodacion/{id}',
        summary: 'Editar tipo de acomodación',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'nombre', type: 'string', example: 'Doble')
                ]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function edit(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(TipoAcomodacion::class);
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
