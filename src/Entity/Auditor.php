<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuditorRepository")
 * @UniqueEntity(fields="ci", message="CI ya existe")
 * @UniqueEntity(fields="rna", message="RNA ya existe")
 */
class Auditor
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
    private $imagen;

    /*
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Por favor, suba una foto del auditor.")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    /*private $imagen;*/

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $nombres;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $apellidos;

    /**
     * @ORM\Column(type="string", length=11, unique=true)
     * @Assert\NotBlank()
     */
    private $ci;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Localidad", inversedBy="auditores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $localidad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entidad", inversedBy="auditores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entidad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cargo", inversedBy="auditores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Nivel", inversedBy="auditores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nivel;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $direccion;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank()
     */
    private $correo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $fea;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     * @Assert\NotBlank()
     */
    private $rna;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaRna;

    public function getSexo()
    {
        $ci = substr($this->getCi(),9,1);

        if($ci%2==0)
        {
            return 'M';
        }
        return 'F';
    }

    public function getEdad()
    {
        $edad = substr($this->getCi(),0,2);
        $sub = substr(date('Y'),1,2);

        if($sub>$edad)
        {
            return $sub-$edad;
        }
        return date('Y')-1900-$edad;
    }

    public function getAnno()
    {
        return date('Y')-5;
    }

    public function __construct()
    {
        $this->activo = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

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

    public function getNombres(): ?string
    {
        return $this->nombres;
    }

    public function setNombres(string $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getCi(): ?string
    {
        return $this->ci;
    }

    public function setCi(string $ci): self
    {
        $this->ci = $ci;

        return $this;
    }

    public function getLocalidad(): ?Localidad
    {
        return $this->localidad;
    }

    public function setLocalidad(?Localidad $localidad): self
    {
        $this->localidad = $localidad;

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

    public function getNivel(): ?Nivel
    {
        return $this->nivel;
    }

    public function setNivel(?Nivel $nivel): self
    {
        $this->nivel = $nivel;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getFea(): ?bool
    {
        return $this->fea;
    }

    public function setFea(bool $fea): self
    {
        $this->fea = $fea;

        return $this;
    }

    public function getRna(): ?string
    {
        return $this->rna;
    }

    public function setRna(string $rna): self
    {
        $this->rna = $rna;

        return $this;
    }

    public function ToString(\DateTimeInterface $fecha)
    {
        $year = date('Y', date_timestamp_get($fecha));
        $month = date('m', date_timestamp_get($fecha));
        $day = date('d', date_timestamp_get($fecha));
        return $year.'-'.$month.'-'.$day;
    }

    public function getFechaRna(): ?\DateTimeInterface
    {
        return $this->fechaRna;
    }

    public function setFechaRna(\DateTimeInterface $fechaRna): self
    {
        $this->fechaRna = $fechaRna;

        return $this;
    }
}
