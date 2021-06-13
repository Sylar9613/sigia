<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CombustibleRepository")
 */
class Combustible
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\NotBlank()
     */
    private $evaluacion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $danoEconomicoCup;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $danoEconomicoOtraMoneda;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccionControl", mappedBy="combustible", cascade={"persist", "remove"})
     */
    private $accionControl;

    public function __construct()
    {
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvaluacion(): ?string
    {
        return $this->evaluacion;
    }

    public function setEvaluacion(string $evaluacion): self
    {
        $this->evaluacion = $evaluacion;

        return $this;
    }

    public function getDanoEconomicoCup(): ?float
    {
        return $this->danoEconomicoCup;
    }

    public function setDanoEconomicoCup(?float $danoEconomicoCup): self
    {
        $this->danoEconomicoCup = $danoEconomicoCup;

        return $this;
    }

    public function getDanoEconomicoOtraMoneda(): ?float
    {
        return $this->danoEconomicoOtraMoneda;
    }

    public function setDanoEconomicoOtraMoneda(?float $danoEconomicoOtraMoneda): self
    {
        $this->danoEconomicoOtraMoneda = $danoEconomicoOtraMoneda;

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

    public function getAccionControl(): ?AccionControl
    {
        return $this->accionControl;
    }

    public function setAccionControl(?AccionControl $accionControl): self
    {
        $this->accionControl = $accionControl;

        // set (or unset) the owning side of the relation if necessary
        $newCombustible = $accionControl === null ? null : $this;
        if ($newCombustible !== $accionControl->getCombustible()) {
            $accionControl->setCombustible($newCombustible);
        }

        return $this;
    }
}
