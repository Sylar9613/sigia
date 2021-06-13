<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganismoRepository")
 * @UniqueEntity(fields="nombre", message="Organismo ya existe")
 */
class Organismo
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
     * @ORM\Column(type="boolean")
     */
    private $controlador;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Osde", mappedBy="organismo")
     */
    private $osdes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    public function __construct()
    {
        $this->osdes = new ArrayCollection();
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

    public function getControlador(): ?bool
    {
        return $this->controlador;
    }

    public function setControlador(bool $controlador): self
    {
        $this->controlador = $controlador;

        return $this;
    }

    /**
     * @return Collection|Osde[]
     */
    public function getOsdes(): Collection
    {
        /**
         * @var Collection|Osde[] $collection
         */
        $collection = new ArrayCollection();

        /**
         * @var Osde $osde
         */
        $osde = $this->osdes;
        foreach ($osde as $item)
        {
            if ($item->getActivo()==1)
            {
                $collection->add($item);
            }
        }
        return $collection;
    }

    public function addOsde(Osde $osde): self
    {
        if (!$this->osdes->contains($osde)) {
            $this->osdes[] = $osde;
            $osde->setOrganismo($this);
        }

        return $this;
    }

    public function removeOsde(Osde $osde): self
    {
        if ($this->osdes->contains($osde)) {
            $this->osdes->removeElement($osde);
            // set the owning side to null (unless already changed)
            if ($osde->getOrganismo() === $this) {
                $osde->setOrganismo(null);
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
