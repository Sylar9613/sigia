<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NivelRepository")
 * @UniqueEntity(fields="nombre", message="Nivel escolar ya existe")
 */
class Nivel
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
     * @ORM\OneToMany(targetEntity="App\Entity\Auditor", mappedBy="nivel")
     */
    private $auditores;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    public function __construct()
    {
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
            $auditore->setNivel($this);
        }

        return $this;
    }

    public function removeAuditore(Auditor $auditore): self
    {
        if ($this->auditores->contains($auditore)) {
            $this->auditores->removeElement($auditore);
            // set the owning side to null (unless already changed)
            if ($auditore->getNivel() === $this) {
                $auditore->setNivel(null);
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
