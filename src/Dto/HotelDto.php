<?php

namespace App\Dto;

final class HotelDto
{
    public ?int $id = null;
    public ?string $nombre = null;
    public ?string $direccion = null;
    public ?string $nit = null;
    public ?int $numeroHabitaciones = null;
    public ?int $ciudadId = null;
    public ?bool $activo = null;

    public static function fromArray(array $data): self
    {
        $d = new self();
        $d->id = isset($data['id']) ? (int)$data['id'] : null;
        $d->nombre = $data['nombre'] ?? null;
        $d->direccion = $data['direccion'] ?? null;
        $d->nit = $data['nit'] ?? null;
        $d->numeroHabitaciones = isset($data['numeroHabitaciones']) ? (int)$data['numeroHabitaciones'] : null;
        $d->ciudadId = isset($data['ciudadId']) ? (int)$data['ciudadId'] : null;
        $d->activo = isset($data['activo']) ? (bool)$data['activo'] : null;

        return $d;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'nit' => $this->nit,
            'numeroHabitaciones' => $this->numeroHabitaciones,
            'ciudadId' => $this->ciudadId,
            'activo' => $this->activo,
        ];
    }
}
