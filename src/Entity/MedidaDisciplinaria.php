<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MedidaDisciplinariaRepository")
 */
class MedidaDisciplinaria
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $categoria;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Responsabilidad", mappedBy="medidaDisciplinaria")
     */
    private $responsabilidad;

    public function __construct()
    {
        $this->activo = true;
        $this->responsabilidad = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

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
            $responsabilidad->setMedidaDisciplinaria($this);
        }

        return $this;
    }

    public function removeResponsabilidad(Responsabilidad $responsabilidad): self
    {
        if ($this->responsabilidad->contains($responsabilidad)) {
            $this->responsabilidad->removeElement($responsabilidad);
            // set the owning side to null (unless already changed)
            if ($responsabilidad->getMedidaDisciplinaria() === $this) {
                $responsabilidad->setMedidaDisciplinaria(null);
            }
        }

        return $this;
    }
}
