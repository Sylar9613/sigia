<?php

namespace App\Entity;

use App\Repository\PhcRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhcRepository")
 */
class Phc
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entidad", inversedBy="phcs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entidad;

    /**
     * @ORM\Column(type="integer")
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $categoria;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $provincia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Municipio", inversedBy="phcs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $municipio;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $fuente;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaDeteccion;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaOcurrencia;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resumen;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AccionControl", inversedBy="phcs")
     */
    private $accionControl;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\HC", mappedBy="phc", cascade={"persist", "remove"})
     */
    private $hc;

    public function __construct()
    {
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getMunicipio(): ?Municipio
    {
        return $this->municipio;
    }

    public function setMunicipio(?Municipio $municipio): self
    {
        $this->municipio = $municipio;

        return $this;
    }

    public function getFuente(): ?string
    {
        return $this->fuente;
    }

    public function setFuente(string $fuente): self
    {
        $this->fuente = $fuente;

        return $this;
    }

    public function getFechaDeteccion(): ?\DateTimeInterface
    {
        return $this->fechaDeteccion;
    }

    public function setFechaDeteccion(\DateTimeInterface $fechaDeteccion): self
    {
        $this->fechaDeteccion = $fechaDeteccion;

        return $this;
    }

    public function getFechaOcurrencia(): ?\DateTimeInterface
    {
        return $this->fechaOcurrencia;
    }

    public function setFechaOcurrencia(\DateTimeInterface $fechaOcurrencia): self
    {
        $this->fechaOcurrencia = $fechaOcurrencia;

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

        return $this;
    }

    public function ToString(\DateTimeInterface $fecha)
    {
        $year = date('Y', date_timestamp_get($fecha));
        $month = date('m', date_timestamp_get($fecha));
        $day = date('d', date_timestamp_get($fecha));
        return $year.'-'.$month.'-'.$day;
    }

    public function getHc(): ?HC
    {
        return $this->hc;
    }

    public function setHc(?HC $hc): self
    {
        $this->hc = $hc;

        return $this;
    }
}
