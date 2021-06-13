<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CargoRepository")
 * @UniqueEntity(fields="nombre", message="Cargo ya existe")
 */
class Cargo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $esContralor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plaza", mappedBy="cargo")
     */
    private $plazas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auditor", mappedBy="cargo")
     */
    private $auditores;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    public function __construct()
    {
        $this->plazas = new ArrayCollection();
        $this->auditores = new ArrayCollection();
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

    public function getEsContralor(): ?bool
    {
        return $this->esContralor;
    }

    public function setEsContralor(bool $esContralor): self
    {
        $this->esContralor = $esContralor;

        return $this;
    }

    /**
     * @return Collection|Plaza[]
     */
    public function getPlazas(): Collection
    {
        /**
         * @var Collection|Plaza[] $collection
         */
        $collection = new ArrayCollection();
        /**
         * @var Plaza $plaza
         */
        $plaza = $this->plazas;
        foreach ($plaza as $item)
        {
            if ($item->getActivo()==1)
            {
                $collection->add($item);
            }
        }

        return $collection;
    }

    public function addPlaza(Plaza $plaza): self
    {
        if (!$this->plazas->contains($plaza)) {
            $this->plazas[] = $plaza;
            $plaza->setCargo($this);
        }

        return $this;
    }

    public function removePlaza(Plaza $plaza): self
    {
        if ($this->plazas->contains($plaza)) {
            $this->plazas->removeElement($plaza);
            // set the owning side to null (unless already changed)
            if ($plaza->getCargo() === $this) {
                $plaza->setCargo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Auditor[]
     */
    public function getAuditores(): Collection
    {
        /**
         * @var Collection|Auditor[] $collection
         */
        $collection = new ArrayCollection();
        /**
         * @var Auditor $auditors
         */
        $auditors = $this->auditores;
        foreach ($auditors as $item)
        {
            if ($item->getActivo()==1)
            {
                $collection->add($item);
            }
        }

        return $collection;
    }

    public function addAuditore(Auditor $auditore): self
    {
        if (!$this->auditores->contains($auditore)) {
            $this->auditores[] = $auditore;
            $auditore->setCargo($this);
        }

        return $this;
    }

    public function removeAuditore(Auditor $auditore): self
    {
        if ($this->auditores->contains($auditore)) {
            $this->auditores->removeElement($auditore);
            // set the owning side to null (unless already changed)
            if ($auditore->getCargo() === $this) {
                $auditore->setCargo(null);
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
