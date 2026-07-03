<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 200)]
    private ?string $cliente = null;

    #[ORM\Column(type: 'string', length: 30)]
    private ?string $numeroDocumento = null;

    #[ORM\Column(type: 'integer')]
    private int $edad;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $fechaReserva;

    #[ORM\ManyToOne(targetEntity: Hotel::class)]
    private ?Hotel $hotel = null;

    #[ORM\ManyToOne(targetEntity: Habitacion::class)]
    private ?Habitacion $habitacion = null;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $fechaIngreso;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $fechaSalida;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCliente(): ?string
    {
        return $this->cliente;
    }

    public function setCliente(string $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getNumeroDocumento(): ?string
    {
        return $this->numeroDocumento;
    }

    public function setNumeroDocumento(string $numeroDocumento): self
    {
        $this->numeroDocumento = $numeroDocumento;

        return $this;
    }

    public function getEdad(): int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): self
    {
        $this->edad = $edad;

        return $this;
    }

    public function getFechaReserva(): \DateTimeInterface
    {
        return $this->fechaReserva;
    }

    public function setFechaReserva(\DateTimeInterface $fechaReserva): self
    {
        $this->fechaReserva = $fechaReserva;

        return $this;
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

    public function getHabitacion(): ?Habitacion
    {
        return $this->habitacion;
    }

    public function setHabitacion(?Habitacion $habitacion): self
    {
        $this->habitacion = $habitacion;

        return $this;
    }

    public function getFechaIngreso(): \DateTimeInterface
    {
        return $this->fechaIngreso;
    }

    public function setFechaIngreso(\DateTimeInterface $fechaIngreso): self
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    public function getFechaSalida(): \DateTimeInterface
    {
        return $this->fechaSalida;
    }

    public function setFechaSalida(\DateTimeInterface $fechaSalida): self
    {
        $this->fechaSalida = $fechaSalida;

        return $this;
    }
}
