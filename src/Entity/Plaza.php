<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlazaRepository")
 * @UniqueEntity(fields={"entidad", "cargo"}, message="Plaza ya existe")
 */
class Plaza
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $plazas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entidad", inversedBy="plazas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entidad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cargo", inversedBy="plazas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cargo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @Assert\IsTrue(message="La cantidad de plazas es inválida.")
     */
    public function isPlazaValid()
    {
        return ($this->plazas >= 1);
    }

    public function __construct()
    {
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlazas(): ?int
    {
        return $this->plazas;
    }

    public function setPlazas(int $plazas): self
    {
        $this->plazas = $plazas;

        return $this;
    }

    public function getEntidad(): ?Entidad
    {
        return $this->entidad;
    }

    public function setEntidad(?Entidad $entidad): self
    {
        $this->entidad = $entidad;

        return $this;
    }

    public function getCargo(): ?Cargo
    {
        return $this->cargo;
    }

    public function setCargo(?Cargo $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }
}
