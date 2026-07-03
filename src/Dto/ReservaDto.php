<?php

namespace App\Dto;

final class ReservaDto
{
    public ?int $id = null;
    public ?string $cliente = null;
    public ?string $numeroDocumento = null;
    public ?int $edad = null;
    public ?string $fechaReserva = null;
    public ?string $fechaIngreso = null;
    public ?string $fechaSalida = null;
    public ?int $hotelId = null;
    public ?int $habitacionId = null;

    public static function fromArray(array $data): self
    {
        $d = new self();
        $d->id = isset($data['id']) ? (int)$data['id'] : null;
        $d->cliente = $data['cliente'] ?? null;
        $d->numeroDocumento = $data['numeroDocumento'] ?? null;
        $d->edad = isset($data['edad']) ? (int)$data['edad'] : null;
        $d->fechaReserva = $data['fechaReserva'] ?? null;
        $d->fechaIngreso = $data['fechaIngreso'] ?? null;
        $d->fechaSalida = $data['fechaSalida'] ?? null;
        $d->hotelId = isset($data['hotelId']) ? (int)$data['hotelId'] : null;
        $d->habitacionId = isset($data['habitacionId']) ? (int)$data['habitacionId'] : null;

        return $d;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'cliente' => $this->cliente,
            'numeroDocumento' => $this->numeroDocumento,
            'edad' => $this->edad,
            'fechaReserva' => $this->fechaReserva,
            'fechaIngreso' => $this->fechaIngreso,
            'fechaSalida' => $this->fechaSalida,
            'hotelId' => $this->hotelId,
            'habitacionId' => $this->habitacionId,
        ];
    }
}
