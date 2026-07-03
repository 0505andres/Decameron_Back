<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true, nullable: false)]
    private string $nombre;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    private string $direccion;

    #[ORM\Column(type: 'string', length: 100, unique: true, nullable: false)]
    private string $nit;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $numeroHabitaciones;

    #[ORM\ManyToOne(targetEntity: Ciudad::class)]
    private ?Ciudad $ciudad = null;

    #[ORM\Column(type: 'boolean')]
    private bool $activo = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getNit(): string
    {
        return $this->nit;
    }

    public function setNit(string $nit): self
    {
        $this->nit = $nit;

        return $this;
    }

    public function getNumeroHabitaciones(): int
    {
        return $this->numeroHabitaciones;
    }

    public function setNumeroHabitaciones(int $numero): self
    {
        $this->numeroHabitaciones = $numero;

        return $this;
    }

    public function getCiudad(): ?Ciudad
    {
        return $this->ciudad;
    }

    public function setCiudad(?Ciudad $ciudad): self
    {
        $this->ciudad = $ciudad;

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
