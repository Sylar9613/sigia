<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MunicipioRepository")
 * @UniqueEntity(fields="nombre", message="Municipio ya existe")
 */
class Municipio
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Localidad", mappedBy="municipio")
     */
    private $localidades;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Phc", mappedBy="municipio")
     */
    private $phcs;

    public function __construct()
    {
        $this->localidades = new ArrayCollection();
        $this->activo = true;
        $this->phcs = new ArrayCollection();
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

    /**
     * @return Collection|Localidad[]
     */
    public function getLocalidades(): Collection
    {
        /**
         * @var Collection|Localidad[] $collection
         */
        $collection = new ArrayCollection();

        /**
         * @var Localidad $localidad
         */
        $localidad = $this->localidades;
        foreach ($localidad as $item)
        {
            if ($item->getActivo()==1)
            {
                $collection->add($item);
            }
        }
        return $collection;
    }

    public function addLocalidade(Localidad $localidade): self
    {
        if (!$this->localidades->contains($localidade)) {
            $this->localidades[] = $localidade;
            $localidade->setMunicipio($this);
        }

        return $this;
    }

    public function removeLocalidade(Localidad $localidade): self
    {
        if ($this->localidades->contains($localidade)) {
            $this->localidades->removeElement($localidade);
            // set the owning side to null (unless already changed)
            if ($localidade->getMunicipio() === $this) {
                $localidade->setMunicipio(null);
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

    /**
     * @return Collection|Phc[]
     */
    public function getPhcs(): Collection
    {
        return $this->phcs;
    }

    public function addPhc(Phc $phc): self
    {
        if (!$this->phcs->contains($phc)) {
            $this->phcs[] = $phc;
            $phc->setMunicipio($this);
        }

        return $this;
    }

    public function removePhc(Phc $phc): self
    {
        if ($this->phcs->contains($phc)) {
            $this->phcs->removeElement($phc);
            // set the owning side to null (unless already changed)
            if ($phc->getMunicipio() === $this) {
                $phc->setMunicipio(null);
            }
        }

        return $this;
    }

}
