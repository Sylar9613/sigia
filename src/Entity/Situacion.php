<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SituacionRepository")
 * @UniqueEntity(fields="nombre", message="Situacion ya existe")
 */
class Situacion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80, unique=true)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $emisor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Phd", mappedBy="situacion")
     */
    private $phds;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    public function __construct()
    {
        $this->phds = new ArrayCollection();
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

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getEmisor(): ?string
    {
        return $this->emisor;
    }

    public function setEmisor(?string $emisor): self
    {
        $this->emisor = $emisor;

        return $this;
    }

    /**
     * @return Collection|Phd[]
     */
    public function getPhds(): Collection
    {
        /**
         * @var Collection|Phd[] $collection
         */
        $collection = new ArrayCollection();
        /**
         * @var Phd $phd
         */
        $phd = $this->phds;
        foreach ($phd as $item)
        {
            if ($item->getActivo()==1)
            {
                $collection->add($item);
            }
        }

        return $collection;
    }

    public function addPhd(Phd $phd): self
    {
        if (!$this->phds->contains($phd)) {
            $this->phds[] = $phd;
            $phd->setSituacion($this);
        }

        return $this;
    }

    public function removePhd(Phd $phd): self
    {
        if ($this->phds->contains($phd)) {
            $this->phds->removeElement($phd);
            // set the owning side to null (unless already changed)
            if ($phd->getSituacion() === $this) {
                $phd->setSituacion(null);
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
}
