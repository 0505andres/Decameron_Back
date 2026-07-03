<?php

namespace App\Dto;

final class HabitacionDto
{
    public ?int $id = null;
    public ?string $codigo = null;
    public ?int $hotelId = null;
    public ?int $tipoHabitacionId = null;
    public ?int $acomodacionId = null;
    public ?bool $libre = null;
    public ?bool $activo = null;

    public static function fromArray(array $data): self
    {
        $d = new self();
        $d->id = isset($data['id']) ? (int)$data['id'] : null;
        $d->codigo = $data['codigo'] ?? null;
        $d->hotelId = isset($data['hotelId']) ? (int)$data['hotelId'] : null;
        $d->tipoHabitacionId = isset($data['tipoHabitacionId']) ? (int)$data['tipoHabitacionId'] : null;
        $d->acomodacionId = isset($data['acomodacionId']) ? (int)$data['acomodacionId'] : null;
        $d->libre = isset($data['libre']) ? (bool)$data['libre'] : null;
        $d->activo = isset($data['activo']) ? (bool)$data['activo'] : null;

        return $d;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'hotelId' => $this->hotelId,
            'tipoHabitacionId' => $this->tipoHabitacionId,
            'acomodacionId' => $this->acomodacionId,
            'libre' => $this->libre,
            'activo' => $this->activo,
        ];
    }
}
