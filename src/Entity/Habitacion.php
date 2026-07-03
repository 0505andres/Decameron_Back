<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'habitacion', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'hotel_habitacion_codigo_unique', columns: ['codigo', 'hotel_id'])
])]
class Habitacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Hotel::class)]
    private ?Hotel $hotel = null;

    #[ORM\Column(type: 'string', length: 5, unique: true)]
    private string $codigo;

    #[ORM\ManyToOne(targetEntity: TipoHabitacion::class)]
    private ?TipoHabitacion $tipoHabitacion = null;

    #[ORM\ManyToOne(targetEntity: TipoAcomodacion::class)]
    private ?TipoAcomodacion $acomodacion = null;

    #[ORM\Column(type: 'boolean')]
    private bool $libre = true;

    #[ORM\Column(type: 'boolean')]
    private bool $activo = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->codigo;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getTipoHabitacion(): ?TipoHabitacion
    {
        return $this->tipoHabitacion;
    }

    public function setTipoHabitacion(?TipoHabitacion $tipo): self
    {
        $this->tipoHabitacion = $tipo;

        return $this;
    }

    public function getAcomodacion(): ?TipoAcomodacion
    {
        return $this->acomodacion;
    }

    public function setAcomodacion(?TipoAcomodacion $acomodacion): self
    {
        $this->acomodacion = $acomodacion;

        return $this;
    }

    public function isLibre(): bool
    {
        return $this->libre;
    }

    public function setLibre(bool $libre): self
    {
        $this->libre = $libre;

        return $this;
    }

    public function isActivo(): bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }
}
