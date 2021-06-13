<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HCRepository")
 */
class HC
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numeroExpediente;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resumen;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $objetoSocialEntidad;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $totalImplicadosEntidad;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $totalImplicadosOtras;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Implicado", mappedBy="hC", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $implicados;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Responsabilidad", mappedBy="hC", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $responsabilidad;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $afectacionEconomicaCUP;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $recuperadoCUP;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Phc", inversedBy="hc", cascade={"persist", "remove"})
     */
    private $phc;

    public function __construct()
    {
        $this->implicados = new ArrayCollection();
        $this->responsabilidad = new ArrayCollection();
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroExpediente(): ?int
    {
        return $this->numeroExpediente;
    }

    public function setNumeroExpediente(int $numeroExpediente): self
    {
        $this->numeroExpediente = $numeroExpediente;

        return $this;
    }

    public function getResumen(): ?string
    {
        return $this->resumen;
    }

    public function setResumen(?string $resumen): self
    {
        $this->resumen = $resumen;

        return $this;
    }

    public function getObjetoSocialEntidad(): ?string
    {
        return $this->objetoSocialEntidad;
    }

    public function setObjetoSocialEntidad(?string $objetoSocialEntidad): self
    {
        $this->objetoSocialEntidad = $objetoSocialEntidad;

        return $this;
    }

    public function getTotalImplicadosEntidad(): ?int
    {
        return $this->totalImplicadosEntidad;
    }

    public function setTotalImplicadosEntidad(?int $totalImplicadosEntidad): self
    {
        $this->totalImplicadosEntidad = $totalImplicadosEntidad;

        return $this;
    }

    public function getTotalImplicadosOtras(): ?int
    {
        return $this->totalImplicadosOtras;
    }

    public function setTotalImplicadosOtras(?int $totalImplicadosOtras): self
    {
        $this->totalImplicadosOtras = $totalImplicadosOtras;

        return $this;
    }

    /**
     * @return Collection|Implicado[]
     */
    public function getImplicados(): Collection
    {
        /**
         * @var Collection|Implicado[] $collection
         */
        $collection = new ArrayCollection();
        /**
         * @var Implicado $implicado
         */
        $implicado = $this->implicados;
        foreach ($implicado as $item)
        {
            if ($item->getActivo()==1)
            {
                $collection->add($item);
            }
        }

        return $collection;
    }

    public function addImplicado(Implicado $implicado): self
    {
        if (!$this->implicados->contains($implicado)) {
            $this->implicados[] = $implicado;
            $implicado->setHC($this);
        }

        return $this;
    }

    public function removeImplicado(Implicado $implicado): self
    {
        if ($this->implicados->contains($implicado)) {
            $this->implicados->removeElement($implicado);
            // set the owning side to null (unless already changed)
            if ($implicado->getHC() === $this) {
                $implicado->setHC(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Responsabilidad[]
     */
    public function getResponsabilidad(): Collection
    {
        /**
         * @var Collection|Responsabilidad[] $collection
         */
        $collection = new ArrayCollection();
        /**
         * @var Responsabilidad $responsabilidad
         */
        $responsabilidad = $this->responsabilidad;
        foreach ($responsabilidad as $item)
        {
            if ($item->getActivo()==1)
            {
                $collection->add($item);
            }
        }

        return $collection;
    }

    public function addResponsabilidad(Responsabilidad $responsabilidad): self
    {
        if (!$this->responsabilidad->contains($responsabilidad)) {
            $this->responsabilidad[] = $responsabilidad;
            $responsabilidad->setHC($this);
        }

        return $this;
    }

    public function removeResponsabilidad(Responsabilidad $responsabilidad): self
    {
        if ($this->responsabilidad->contains($responsabilidad)) {
            $this->responsabilidad->removeElement($responsabilidad);
            // set the owning side to null (unless already changed)
            if ($responsabilidad->getHC() === $this) {
                $responsabilidad->setHC(null);
            }
        }

        return $this;
    }

    public function getAfectacionEconomicaCUP(): ?float
    {
        return $this->afectacionEconomicaCUP;
    }

    public function setAfectacionEconomicaCUP(?float $afectacionEconomicaCUP): self
    {
        $this->afectacionEconomicaCUP = $afectacionEconomicaCUP;

        return $this;
    }

    public function getRecuperadoCUP(): ?float
    {
        return $this->recuperadoCUP;
    }

    public function setRecuperadoCUP(?float $recuperadoCUP): self
    {
        $this->recuperadoCUP = $recuperadoCUP;

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

    public function getallImplicados()
    {
        /**
         * @var Collection|ArrayCollection[] $collection
         */
        $collection = new ArrayCollection();
        /**
         * @var Implicado $implicado
         */
        $implicado = $this->implicados;
        foreach ($implicado as $item)
        {
            if ($item->getActivo()==1)
            {
                $collection->add($item->getNombre());
            }
        }

        return $collection;
    }

    public function getTotalImplicados()
    {
        return $this->totalImplicadosEntidad+$this->totalImplicadosOtras;
    }

    public function getPhc(): ?Phc
    {
        return $this->phc;
    }

    public function setPhc(?Phc $phc): self
    {
        $this->phc = $phc;

        // set (or unset) the owning side of the relation if necessary
        $newHc = $phc === null ? null : $this;
        if ($newHc !== $phc->getHc()) {
            $phc->setHc($newHc);
        }

        return $this;
    }
}
