<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OsdeRepository")
 * @UniqueEntity(fields="nombre", message="Osde ya existe")
 */
class Osde
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=70, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organismo", inversedBy="osdes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organismo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entidad", mappedBy="osde")
     */
    private $entidades;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    public function __construct()
    {
        $this->entidades = new ArrayCollection();
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

    public function getOrganismo(): ?Organismo
    {
        return $this->organismo;
    }

    public function setOrganismo(?Organismo $organismo): self
    {
        $this->organismo = $organismo;

        return $this;
    }

    /**
     * @return Collection|Entidad[]
     */
    public function getEntidades(): Collection
    {
        /**
         * @var Collection|Entidad[] $collection
         */
        $collection = new ArrayCollection();

        /**
         * @var Entidad $entidad
         */
        $entidad = $this->entidades;
        foreach ($entidad as $item)
        {
            if ($item->getActivo()==1)
            {
                $collection->add($item);
            }
        }
        return $collection;
    }

    public function addEntidade(Entidad $entidade): self
    {
        if (!$this->entidades->contains($entidade)) {
            $this->entidades[] = $entidade;
            $entidade->setOsde($this);
        }

        return $this;
    }

    public function removeEntidade(Entidad $entidade): self
    {
        if ($this->entidades->contains($entidade)) {
            $this->entidades->removeElement($entidade);
            // set the owning side to null (unless already changed)
            if ($entidade->getOsde() === $this) {
                $entidade->setOsde(null);
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
