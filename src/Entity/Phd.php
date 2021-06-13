<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhdRepository")
 */
class Phd
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $unidadOrganizativa;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entidad", inversedBy="phds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entidad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TipoAccion", inversedBy="phds")
     */
    private $tipoAccion;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Situacion", inversedBy="phds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $situacion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroExpediente;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroCausa;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $danoEconomicoCup;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $danoEconomicoOtraMoneda;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CausaCondicion", inversedBy="phds")
     */
    private $causaCondicion;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sintesis;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Implicado", mappedBy="phd", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $implicados;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccionControl", inversedBy="phds")
     */
    private $accionControl;

    public function __construct()
    {
        $this->implicados = new ArrayCollection();
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUnidadOrganizativa(): ?string
    {
        return $this->unidadOrganizativa;
    }

    public function setUnidadOrganizativa(string $unidadOrganizativa): self
    {
        $this->unidadOrganizativa = $unidadOrganizativa;

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

    public function getTipoAccion(): ?TipoAccion
    {
        return $this->tipoAccion;
    }

    public function setTipoAccion(?TipoAccion $tipoAccion): self
    {
        $this->tipoAccion = $tipoAccion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getSituacion(): ?Situacion
    {
        return $this->situacion;
    }

    public function setSituacion(?Situacion $situacion): self
    {
        $this->situacion = $situacion;

        return $this;
    }

    public function getNumeroExpediente(): ?int
    {
        return $this->numeroExpediente;
    }

    public function setNumeroExpediente(?int $numeroExpediente): self
    {
        $this->numeroExpediente = $numeroExpediente;

        return $this;
    }

    public function getNumeroCausa(): ?int
    {
        return $this->numeroCausa;
    }

    public function setNumeroCausa(?int $numeroCausa): self
    {
        $this->numeroCausa = $numeroCausa;

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

    public function getCausaCondicion(): ?CausaCondicion
    {
        return $this->causaCondicion;
    }

    public function setCausaCondicion(?CausaCondicion $causaCondicion): self
    {
        $this->causaCondicion = $causaCondicion;

        return $this;
    }

    public function getSintesis(): ?string
    {
        return $this->sintesis;
    }

    public function setSintesis(?string $sintesis): self
    {
        $this->sintesis = $sintesis;

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
            $implicado->setPhd($this);
        }

        return $this;
    }

    public function removeImplicado(Implicado $implicado): self
    {
        if ($this->implicados->contains($implicado)) {
            $this->implicados->removeElement($implicado);
            // set the owning side to null (unless already changed)
            if ($implicado->getPhd() === $this) {
                $implicado->setPhd(null);
            }
        }

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

    public function ToString(\DateTimeInterface $fecha)
    {
        $year = date('Y', date_timestamp_get($fecha));
        $month = date('m', date_timestamp_get($fecha));
        $day = date('d', date_timestamp_get($fecha));
        return $year.'-'.$month.'-'.$day;
    }

    public function getAccionControl(): ?AccionControl
    {
        return $this->accionControl;
    }

    public function setAccionControl(?AccionControl $accionControl): self
    {
        $this->accionControl = $accionControl;

        return $this;
    }
}
