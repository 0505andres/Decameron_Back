<?php

namespace App\Controller\Api;

use App\Entity\Habitacion;
use App\Entity\Hotel;
use App\Entity\TipoHabitacion;
use App\Entity\TipoAcomodacion;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/habitacion')]
#[OA\Tag(name: 'Habitacion')]
class HabitacionController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/habitacion',
        summary: 'Listar habitaciones',
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $items = $em->getRepository(Habitacion::class)->findAll();

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
                    new OA\Property(property: 'codigo', type: 'string', example: 'H100'),
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
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['codigo']) || empty($data['hotelId'])) {
            throw new BusinessException('Campos requeridos faltantes');
        }

        $hotel = $em->getRepository(Hotel::class)->find($data['hotelId']);
        if (!$hotel) throw new BusinessException('Hotel no encontrado');

        $tipo = null;
        if (!empty($data['tipoHabitacionId'])) {
            $tipo = $em->getRepository(TipoHabitacion::class)->find($data['tipoHabitacionId']);
            if (!$tipo) throw new BusinessException('TipoHabitacion no encontrado');
        }

        $acom = null;
        if (!empty($data['acomodacionId'])) {
            $acom = $em->getRepository(TipoAcomodacion::class)->find($data['acomodacionId']);
            if (!$acom) throw new BusinessException('TipoAcomodacion no encontrado');
        }

        $h = new Habitacion();
        $h->setCodigo($data['codigo']);
        $h->setHotel($hotel);
        $h->setTipoHabitacion($tipo);
        $h->setAcomodacion($acom);
        if (isset($data['libre'])) $h->setLibre((bool)$data['libre']);
        if (isset($data['activo'])) $h->setActivo((bool)$data['activo']);

        $em->persist($h);
        $em->flush();

        return $this->json($h, 201);
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
                    new OA\Property(property: 'codigo', type: 'string', example: 'H100'),
                    new OA\Property(property: 'hotelId', type: 'integer', example: 1),
                    new OA\Property(property: 'tipoHabitacionId', type: 'integer', example: 1),
                    new OA\Property(property: 'acomodacionId', type: 'integer', example: 1),
                    new OA\Property(property: 'libre', type: 'boolean', example: false),
                    new OA\Property(property: 'activo', type: 'boolean', example: true)
                ]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function edit(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(Habitacion::class);
        $h = $repo->find($id);
        if (!$h) return $this->json(['error' => 'Not found'], 404);

        $data = json_decode($request->getContent(), true);
        if (isset($data['codigo'])) $h->setCodigo($data['codigo']);
        if (isset($data['hotelId'])) {
            $hotel = $em->getRepository(Hotel::class)->find($data['hotelId']);
            if (!$hotel) throw new BusinessException('Hotel no encontrado');
            $h->setHotel($hotel);
        }
        if (isset($data['tipoHabitacionId'])) {
            $tipo = $em->getRepository(TipoHabitacion::class)->find($data['tipoHabitacionId']);
            if (!$tipo) throw new BusinessException('TipoHabitacion no encontrado');
            $h->setTipoHabitacion($tipo);
        }
        if (isset($data['acomodacionId'])) {
            $acom = $em->getRepository(TipoAcomodacion::class)->find($data['acomodacionId']);
            if (!$acom) throw new BusinessException('TipoAcomodacion no encontrado');
            $h->setAcomodacion($acom);
        }
        if (isset($data['libre'])) $h->setLibre((bool)$data['libre']);
        if (isset($data['activo'])) $h->setActivo((bool)$data['activo']);

        $em->flush();

        return $this->json($h);
    }
}
