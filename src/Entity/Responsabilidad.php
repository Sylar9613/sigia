<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResponsabilidadRepository")
 */
class Responsabilidad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HC", inversedBy="responsabilidad")
     */
    private $hC;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $medidasTotal;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $medidasPendientes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MedidaDisciplinaria", inversedBy="responsabilidad")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medidaDisciplinaria;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Implicado", inversedBy="responsabilidad", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $implicado;

    public function __construct()
    {
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHC(): ?HC
    {
        return $this->hC;
    }

    public function setHC(?HC $hC): self
    {
        $this->hC = $hC;

        return $this;
    }

    public function getMedidasTotal(): ?int
    {
        return $this->medidasTotal;
    }

    public function setMedidasTotal(?int $medidasTotal): self
    {
        $this->medidasTotal = $medidasTotal;

        return $this;
    }

    public function getMedidasPendientes(): ?int
    {
        return $this->medidasPendientes;
    }

    public function setMedidasPendientes(?int $medidasPendientes): self
    {
        $this->medidasPendientes = $medidasPendientes;

        return $this;
    }

    public function getMedidaDisciplinaria(): ?MedidaDisciplinaria
    {
        return $this->medidaDisciplinaria;
    }

    public function setMedidaDisciplinaria(?MedidaDisciplinaria $medidaDisciplinaria): self
    {
        $this->medidaDisciplinaria = $medidaDisciplinaria;

        return $this;
    }

    public function getImplicado(): ?Implicado
    {
        return $this->implicado;
    }

    public function setImplicado(Implicado $implicado): self
    {
        $this->implicado = $implicado;

        return $this;
    }
}
