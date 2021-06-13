<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EntidadRepository")
 * @UniqueEntity(fields="nombre", message="La entidad ya existe")
 * @UniqueEntity(fields="nit", message="El NIT ya existe")
 * @UniqueEntity(fields="reeup", message="El REEUP ya existe")
 */
class Entidad
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
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Osde", inversedBy="entidades")
     * @ORM\JoinColumn(nullable=false)
     */
    private $osde;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ai;

    /**
     * @ORM\Column(type="string", length=11, unique=true)
     * @Assert\NotBlank()
     */
    private $nit;

    /**
     * @ORM\Column(type="string", length=11, unique=true)
     * @Assert\NotBlank()
     */
    private $reeup;

    /**
     * @ORM\Column(type="boolean")
     */
    private $uai;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ucai;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plaza", mappedBy="entidad")
     */
    private $plazas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auditor", mappedBy="entidad")
     */
    private $auditores;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Phd", mappedBy="entidad")
     */
    private $phds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="entidad", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Phc", mappedBy="entidad")
     */
    private $phcs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AccionControl", mappedBy="entidad")
     */
    private $accionControl;

    public function __construct()
    {
        $this->plazas = new ArrayCollection();
        $this->auditores = new ArrayCollection();
        $this->phds = new ArrayCollection();
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

    public function getOsde(): ?Osde
    {
        return $this->osde;
    }

    public function setOsde(?Osde $osde): self
    {
        $this->osde = $osde;

        return $this;
    }

    public function getAi(): ?bool
    {
        return $this->ai;
    }

    public function setAi(bool $ai): self
    {
        $this->ai = $ai;

        return $this;
    }

    public function getNit(): ?string
    {
        return $this->nit;
    }

    public function setNit(string $nit): self
    {
        $this->nit = $nit;

        return $this;
    }

    public function getReeup(): ?string
    {
        return $this->reeup;
    }

    public function setReeup(string $reeup): self
    {
        $this->reeup = $reeup;

        return $this;
    }

    public function getUai(): ?bool
    {
        return $this->uai;
    }

    public function setUai(bool $uai): self
    {
        $this->uai = $uai;

        return $this;
    }

    public function getUcai(): ?bool
    {
        return $this->ucai;
    }

    public function setUcai(bool $ucai): self
    {
        $this->ucai = $ucai;

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
            $plaza->setEntidad($this);
        }

        return $this;
    }

    public function removePlaza(Plaza $plaza): self
    {
        if ($this->plazas->contains($plaza)) {
            $this->plazas->removeElement($plaza);
            // set the owning side to null (unless already changed)
            if ($plaza->getEntidad() === $this) {
                $plaza->setEntidad(null);
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
            $auditore->setEntidad($this);
        }

        return $this;
    }

    public function removeAuditore(Auditor $auditore): self
    {
        if ($this->auditores->contains($auditore)) {
            $this->auditores->removeElement($auditore);
            // set the owning side to null (unless already changed)
            if ($auditore->getEntidad() === $this) {
                $auditore->setEntidad(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Phd[]
     */
    public function getPhds(): Collection
    {
        return $this->phds;
    }

    public function addPhd(Phd $phd): self
    {
        if (!$this->phds->contains($phd)) {
            $this->phds[] = $phd;
            $phd->setEntidad($this);
        }

        return $this;
    }

    public function removePhd(Phd $phd): self
    {
        if ($this->phds->contains($phd)) {
            $this->phds->removeElement($phd);
            // set the owning side to null (unless already changed)
            if ($phd->getEntidad() === $this) {
                $phd->setEntidad(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newEntidad = $user === null ? null : $this;
        if ($newEntidad !== $user->getEntidad()) {
            $user->setEntidad($newEntidad);
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
            $phc->setEntidad($this);
        }

        return $this;
    }

    public function removePhc(Phc $phc): self
    {
        if ($this->phcs->contains($phc)) {
            $this->phcs->removeElement($phc);
            // set the owning side to null (unless already changed)
            if ($phc->getEntidad() === $this) {
                $phc->setEntidad(null);
            }
        }

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
        if ($this !== $accionControl->getEntidad()) {
            $accionControl->setEntidad($this);
        }

        return $this;
    }
}
