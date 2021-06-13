<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImplicadoRepository")
 */
class Implicado
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     */
    private $cargo;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $categoriaOcupacional;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $escolaridad;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pcc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phd", inversedBy="implicados")
     */
    private $phd;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nivelDireccion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ujc;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $edad;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $sexo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HC", inversedBy="implicados")
     */
    private $hC;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Responsabilidad", mappedBy="implicado", cascade={"persist", "remove"})
     */
    private $responsabilidad;

    public function __construct()
    {
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCargo(): ?string
    {
        return $this->cargo;
    }

    public function setCargo(string $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getCategoriaOcupacional(): ?string
    {
        return $this->categoriaOcupacional;
    }

    public function setCategoriaOcupacional(string $categoriaOcupacional): self
    {
        $this->categoriaOcupacional = $categoriaOcupacional;

        return $this;
    }

    public function getEscolaridad(): ?string
    {
        return $this->escolaridad;
    }

    public function setEscolaridad(string $escolaridad): self
    {
        $this->escolaridad = $escolaridad;

        return $this;
    }

    public function getPcc(): ?bool
    {
        return $this->pcc;
    }

    public function setPcc(bool $pcc): self
    {
        $this->pcc = $pcc;

        return $this;
    }

    public function getPhd(): ?Phd
    {
        return $this->phd;
    }

    public function setPhd(?Phd $phd): self
    {
        $this->phd = $phd;

        return $this;
    }

    public function getNivelDireccion(): ?string
    {
        return $this->nivelDireccion;
    }

    public function setNivelDireccion(string $nivelDireccion): self
    {
        $this->nivelDireccion = $nivelDireccion;

        return $this;
    }

    public function getUjc(): ?bool
    {
        return $this->ujc;
    }

    public function setUjc(bool $ujc): self
    {
        $this->ujc = $ujc;

        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): self
    {
        $this->edad = $edad;

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): self
    {
        $this->sexo = $sexo;

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

    public function getHC(): ?HC
    {
        return $this->hC;
    }

    public function setHC(?HC $hC): self
    {
        $this->hC = $hC;

        return $this;
    }

    public function getResponsabilidad(): ?Responsabilidad
    {
        return $this->responsabilidad;
    }

    public function setResponsabilidad(Responsabilidad $responsabilidad): self
    {
        $this->responsabilidad = $responsabilidad;

        // set the owning side of the relation if necessary
        if ($this !== $responsabilidad->getImplicado()) {
            $responsabilidad->setImplicado($this);
        }

        return $this;
    }
}
