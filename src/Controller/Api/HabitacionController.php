<?php

namespace App\Controller\Api;

use App\Entity\Habitacion;
use App\Entity\Hotel;
use App\Entity\TipoHabitacion;
use App\Entity\TipoAcomodacion;
use App\Exception\BusinessException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/habitacion')]
class HabitacionController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $items = $em->getRepository(Habitacion::class)->findAll();

        return $this->json($items);
    }

    #[Route('', methods: ['POST'])]
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
