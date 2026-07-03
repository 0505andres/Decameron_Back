<?php

namespace App\Dto;

final class TipoAcomodacionDto
{
    public ?int $id = null;
    public ?string $nombre = null;

    public static function fromArray(array $data): self
    {
        $d = new self();
        $d->id = isset($data['id']) ? (int)$data['id'] : null;
        $d->nombre = $data['nombre'] ?? null;

        return $d;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
        ];
    }
}
