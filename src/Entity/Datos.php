<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DatosRepository")
 */
class Datos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $databaseFilename;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatabaseFilename(): ?string
    {
        return $this->databaseFilename;
    }

    public function setDatabaseFilename(string $databaseFilename): self
    {
        $this->databaseFilename = $databaseFilename;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
