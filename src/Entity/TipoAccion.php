<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TipoAccionRepository")
 * @UniqueEntity(fields="nombre", message="Tipo de acción ya existe")
 */
class TipoAccion
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
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Phd", mappedBy="tipoAccion")
     */
    private $phds;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AccionControl", mappedBy="tipoAccion")
     */
    private $accionControl;

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
            $phd->setTipoAccion($this);
        }

        return $this;
    }

    public function removePhd(Phd $phd): self
    {
        if ($this->phds->contains($phd)) {
            $this->phds->removeElement($phd);
            // set the owning side to null (unless already changed)
            if ($phd->getTipoAccion() === $this) {
                $phd->setTipoAccion(null);
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

    public function getAccionControl(): ?AccionControl
    {
        return $this->accionControl;
    }

    public function setAccionControl(AccionControl $accionControl): self
    {
        $this->accionControl = $accionControl;

        // set the owning side of the relation if necessary
        if ($this !== $accionControl->getTipoAccion()) {
            $accionControl->setTipoAccion($this);
        }

        return $this;
    }
}
