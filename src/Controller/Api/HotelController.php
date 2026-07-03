<?php

namespace App\Controller\Api;

use App\Entity\Hotel;
use App\Entity\Ciudad;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/hotel')]
#[OA\Tag(name: 'Hotel')]
class HotelController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/hotel',
        summary: 'Listar hoteles',
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $items = $em->getRepository(Hotel::class)->findAll();

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
                    new OA\Property(property: 'nombre', type: 'string', example: 'Hotel Caribe'),
                    new OA\Property(property: 'direccion', type: 'string', example: 'Calle 1 #2-3'),
                    new OA\Property(property: 'nit', type: 'string', example: '900123456'),
                    new OA\Property(property: 'numeroHabitaciones', type: 'integer', example: 120),
                    new OA\Property(property: 'ciudadId', type: 'integer', example: 1),
                    new OA\Property(property: 'activo', type: 'boolean', example: true)
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Created')]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['nombre']) || empty($data['direccion']) || empty($data['nit']) || !isset($data['numeroHabitaciones'])) {
            throw new BusinessException('Campos requeridos faltantes');
        }

        $ciudad = null;
        if (!empty($data['ciudadId'])) {
            $ciudad = $em->getRepository(Ciudad::class)->find($data['ciudadId']);
            if (!$ciudad) {
                throw new BusinessException('Ciudad no encontrada');
            }
        }

        $hotel = new Hotel();
        $hotel->setNombre($data['nombre']);
        $hotel->setDireccion($data['direccion']);
        $hotel->setNit($data['nit']);
        $hotel->setNumeroHabitaciones((int)$data['numeroHabitaciones']);
        $hotel->setCiudad($ciudad);
        if (isset($data['activo'])) {
            $hotel->setActivo((bool)$data['activo']);
        }

        $em->persist($hotel);
        $em->flush();

        return $this->json($hotel, 201);
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
                    new OA\Property(property: 'nombre', type: 'string', example: 'Hotel Caribe'),
                    new OA\Property(property: 'direccion', type: 'string', example: 'Calle 1 #2-3'),
                    new OA\Property(property: 'nit', type: 'string', example: '900123456'),
                    new OA\Property(property: 'numeroHabitaciones', type: 'integer', example: 120),
                    new OA\Property(property: 'ciudadId', type: 'integer', example: 1),
                    new OA\Property(property: 'activo', type: 'boolean', example: true)
                ]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function edit(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(Hotel::class);
        $hotel = $repo->find($id);
        if (!$hotel) {
            return $this->json(['error' => 'Not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['nombre'])) $hotel->setNombre($data['nombre']);
        if (isset($data['direccion'])) $hotel->setDireccion($data['direccion']);
        if (isset($data['nit'])) $hotel->setNit($data['nit']);
        if (isset($data['numeroHabitaciones'])) $hotel->setNumeroHabitaciones((int)$data['numeroHabitaciones']);
        if (isset($data['ciudadId'])) {
            $ciudad = $em->getRepository(Ciudad::class)->find($data['ciudadId']);
            if (!$ciudad) throw new BusinessException('Ciudad no encontrada');
            $hotel->setCiudad($ciudad);
        }

        if (isset($data['activo'])) $hotel->setActivo((bool)$data['activo']);

        $em->flush();

        return $this->json($hotel);
    }
}
