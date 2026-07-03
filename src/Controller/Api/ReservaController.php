<?php

namespace App\Controller\Api;

use App\Entity\Reserva;
use App\Entity\Hotel;
use App\Entity\Habitacion;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/reserva')]
#[OA\Tag(name: 'Reserva')]
class ReservaController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/reserva',
        summary: 'Listar reservas',
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $items = $em->getRepository(Reserva::class)->findAll();

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
        responses: [new OA\Response(response: 201, description: 'Created')]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        foreach (['cliente','numeroDocumento','edad','fechaReserva','fechaIngreso','fechaSalida','hotelId','habitacionId'] as $f) {
            if (!isset($data[$f]) || $data[$f] === '') {
                throw new BusinessException('Campo requerido faltante: ' . $f);
            }
        }

        $hotel = $em->getRepository(Hotel::class)->find($data['hotelId']);
        if (!$hotel) throw new BusinessException('Hotel no encontrado');

        $habitacion = $em->getRepository(Habitacion::class)->find($data['habitacionId']);
        if (!$habitacion) throw new BusinessException('Habitacion no encontrada');

        $res = new Reserva();
        $res->setCliente($data['cliente']);
        $res->setNumeroDocumento($data['numeroDocumento']);
        $res->setEdad((int)$data['edad']);
        $res->setFechaReserva(new \DateTime($data['fechaReserva']));
        $res->setFechaIngreso(new \DateTime($data['fechaIngreso']));
        $res->setFechaSalida(new \DateTime($data['fechaSalida']));
        $res->setHotel($hotel);
        $res->setHabitacion($habitacion);

        $em->persist($res);
        $em->flush();

        return $this->json($res, 201);
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
    public function edit(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(Reserva::class);
        $res = $repo->find($id);
        if (!$res) return $this->json(['error' => 'Not found'], 404);

        $data = json_decode($request->getContent(), true);
        if (isset($data['cliente'])) $res->setCliente($data['cliente']);
        if (isset($data['numeroDocumento'])) $res->setNumeroDocumento($data['numeroDocumento']);
        if (isset($data['edad'])) $res->setEdad((int)$data['edad']);
        if (isset($data['fechaReserva'])) $res->setFechaReserva(new \DateTime($data['fechaReserva']));
        if (isset($data['fechaIngreso'])) $res->setFechaIngreso(new \DateTime($data['fechaIngreso']));
        if (isset($data['fechaSalida'])) $res->setFechaSalida(new \DateTime($data['fechaSalida']));

        if (isset($data['hotelId'])) {
            $hotel = $em->getRepository(Hotel::class)->find($data['hotelId']);
            if (!$hotel) throw new BusinessException('Hotel no encontrado');
            $res->setHotel($hotel);
        }

        if (isset($data['habitacionId'])) {
            $hab = $em->getRepository(Habitacion::class)->find($data['habitacionId']);
            if (!$hab) throw new BusinessException('Habitacion no encontrada');
            $res->setHabitacion($hab);
        }

        $em->flush();

        return $this->json($res);
    }
}
