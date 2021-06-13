<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccionControlRepository")
 */
class AccionControl
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $ordenTrabajo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Entidad", inversedBy="accionControl")
     */
    private $entidad;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TipoAccion", inversedBy="accionControl")
     */
    private $tipoAccion;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $directivas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Particularidad")
     */
    private $particularidad;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $auditorPlan;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $auditorReal;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $diasPlan;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $diasReal;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $auditorXDiaPlan;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $auditorXDiaReal;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaInicioPlan;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaFinPlan;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaInicioReal;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaFinReal;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $calificacion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Phd", mappedBy="accionControl")
     */
    private $phds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Phc", mappedBy="accionControl")
     */
    private $phcs;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Combustible", inversedBy="accionControl", cascade={"persist", "remove"})
     */
    private $combustible;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $danoCUP;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $danoCUC;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $danoOtraMoneda;

    /**
     * @ORM\Column(type="boolean")
     */
    private $planMedidas;

    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;

    public function __construct()
    {
        $this->phds = new ArrayCollection();
        $this->phcs = new ArrayCollection();
        $this->Hc = new ArrayCollection();
        $this->activo = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdenTrabajo(): ?string
    {
        return $this->ordenTrabajo;
    }

    public function setOrdenTrabajo(string $ordenTrabajo): self
    {
        $this->ordenTrabajo = $ordenTrabajo;

        return $this;
    }

    public function getEntidad(): ?Entidad
    {
        return $this->entidad;
    }

    public function setEntidad(Entidad $entidad): self
    {
        $this->entidad = $entidad;

        return $this;
    }

    public function getTipoAccion(): ?TipoAccion
    {
        return $this->tipoAccion;
    }

    public function setTipoAccion(TipoAccion $tipoAccion): self
    {
        $this->tipoAccion = $tipoAccion;

        return $this;
    }

    public function getDirectivas(): ?string
    {
        return $this->directivas;
    }

    public function setDirectivas(?string $directivas): self
    {
        $this->directivas = $directivas;

        return $this;
    }

    public function getParticularidad(): ?Particularidad
    {
        return $this->particularidad;
    }

    public function setParticularidad(Particularidad $particularidad): self
    {
        $this->particularidad = $particularidad;

        return $this;
    }

    public function getAuditorPlan(): ?int
    {
        return $this->auditorPlan;
    }

    public function setAuditorPlan(?int $auditorPlan): self
    {
        $this->auditorPlan = $auditorPlan;

        return $this;
    }

    public function getAuditorReal(): ?int
    {
        return $this->auditorReal;
    }

    public function setAuditorReal(?int $auditorReal): self
    {
        $this->auditorReal = $auditorReal;

        return $this;
    }

    public function getDiasPlan(): ?int
    {
        return $this->diasPlan;
    }

    public function setDiasPlan(?int $diasPlan): self
    {
        $this->diasPlan = $diasPlan;

        return $this;
    }

    public function getDiasReal(): ?int
    {
        return $this->diasReal;
    }

    public function setDiasReal(?int $diasReal): self
    {
        $this->diasReal = $diasReal;

        return $this;
    }

    public function getAuditorXDiaPlan(): ?int
    {
        return $this->auditorXDiaPlan;
    }

    public function setAuditorXDiaPlan(?int $auditorXDiaPlan): self
    {
        $this->auditorXDiaPlan = $auditorXDiaPlan;

        return $this;
    }

    public function getAuditorXDiaReal(): ?int
    {
        return $this->auditorXDiaReal;
    }

    public function setAuditorXDiaReal(?int $auditorXDiaReal): self
    {
        $this->auditorXDiaReal = $auditorXDiaReal;

        return $this;
    }

    public function getFechaInicioPlan(): ?\DateTimeInterface
    {
        return $this->fechaInicioPlan;
    }

    public function setFechaInicioPlan(\DateTimeInterface $fechaInicioPlan): self
    {
        $this->fechaInicioPlan = $fechaInicioPlan;

        return $this;
    }

    public function getFechaFinPlan(): ?\DateTimeInterface
    {
        return $this->fechaFinPlan;
    }

    public function setFechaFinPlan(\DateTimeInterface $fechaFinPlan): self
    {
        $this->fechaFinPlan = $fechaFinPlan;

        return $this;
    }

    public function getFechaInicioReal(): ?\DateTimeInterface
    {
        return $this->fechaInicioReal;
    }

    public function setFechaInicioReal(\DateTimeInterface $fechaInicioReal): self
    {
        $this->fechaInicioReal = $fechaInicioReal;

        return $this;
    }

    public function getFechaFinReal(): ?\DateTimeInterface
    {
        return $this->fechaFinReal;
    }

    public function setFechaFinReal(\DateTimeInterface $fechaFinReal): self
    {
        $this->fechaFinReal = $fechaFinReal;

        return $this;
    }

    public function getCalificacion(): ?string
    {
        return $this->calificacion;
    }

    public function setCalificacion(?string $calificacion): self
    {
        $this->calificacion = $calificacion;

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
            $phd->setAccionControl($this);
        }

        return $this;
    }

    public function removePhd(Phd $phd): self
    {
        if ($this->phds->contains($phd)) {
            $this->phds->removeElement($phd);
            // set the owning side to null (unless already changed)
            if ($phd->getAccionControl() === $this) {
                $phd->setAccionControl(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Phc[]
     */
    public function getPhcs(): Collection
    {
        /**
         * @var Collection|Phc[] $collection
         */
        $collection = new ArrayCollection();
        /**
         * @var Phc $phc
         */
        $phc = $this->phcs;
        foreach ($phc as $item)
        {
            if ($item->getActivo()==1)
            {
                $collection->add($item);
            }
        }

        return $collection;
    }

    public function addPhc(Phc $phc): self
    {
        if (!$this->phcs->contains($phc)) {
            $this->phcs[] = $phc;
            $phc->setAccionControl($this);
        }

        return $this;
    }

    public function removePhc(Phc $phc): self
    {
        if ($this->phcs->contains($phc)) {
            $this->phcs->removeElement($phc);
            // set the owning side to null (unless already changed)
            if ($phc->getAccionControl() === $this) {
                $phc->setAccionControl(null);
            }
        }

        return $this;
    }

    public function getCombustible(): ?Combustible
    {
        return $this->combustible;
    }

    public function setCombustible(?Combustible $combustible): self
    {
        $this->combustible = $combustible;

        return $this;
    }

    public function getDanoCUP(): ?float
    {
        return $this->danoCUP;
    }

    public function setDanoCUP(?float $danoCUP): self
    {
        $this->danoCUP = $danoCUP;

        return $this;
    }

    public function getDanoCUC(): ?float
    {
        return $this->danoCUC;
    }

    public function setDanoCUC(?float $danoCUC): self
    {
        $this->danoCUC = $danoCUC;

        return $this;
    }

    public function getDanoOtraMoneda(): ?float
    {
        return $this->danoOtraMoneda;
    }

    public function setDanoOtraMoneda(?float $danoOtraMoneda): self
    {
        $this->danoOtraMoneda = $danoOtraMoneda;

        return $this;
    }

    public function getPlanMedidas(): ?bool
    {
        return $this->planMedidas;
    }

    public function setPlanMedidas(bool $planMedidas): self
    {
        $this->planMedidas = $planMedidas;

        return $this;
    }

    /**
     * @Assert\IsTrue(message="La fecha final planificada debe ser mayor que la fecha inicial")
     */
    public function isFechaPlanValidate()
    {
        return $this->fechaInicioPlan < $this->fechaFinPlan;
    }

    /**
     * @Assert\IsTrue(message="La fecha final real debe ser mayor que la fecha inicial")
     */
    public function isFechaRealValidate()
    {
        return $this->fechaInicioReal < $this->fechaFinReal;
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

    public function ToString(\DateTimeInterface $fecha)
    {
        $year = date('Y', date_timestamp_get($fecha));
        $month = date('m', date_timestamp_get($fecha));
        $day = date('d', date_timestamp_get($fecha));
        return $year.'-'.$month.'-'.$day;
    }

    public function DateTimeToString(\DateTimeInterface $fecha)
    {
        $year = date('Y', date_timestamp_get($fecha));
        $month = date('m', date_timestamp_get($fecha));
        $day = date('d', date_timestamp_get($fecha));
        $hour = date('h', date_timestamp_get($fecha));
        $min = date('i', date_timestamp_get($fecha));
        $seg = date('s', date_timestamp_get($fecha));

        return $year.''.$month.''.$day.''.$hour.''.$min.''.$seg;
    }

    public function getHcs()
    {
        $contador = 0;
        /**
         * @var Phc $phc
         */
        $phc = $this->phcs;
        foreach ($phc as $item)
        {
            if ($item->getActivo()==1 && $item->getHc())
            {
                $contador++;
            }
        }

        return $contador;
    }
}
