<?php

namespace App\Entity;

use App\Repository\PointagesRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as MyConstraints;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PointagesRepository::class)
 * @UniqueEntity(
 *     fields={"date", "id_utilisateur"}
 * )
 */
class Pointages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     * @MyConstraints\Time
     */
    private $duree;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="pointages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Chantier::class, inversedBy="pointages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_chantier;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }

    public function getIdChantier(): ?Chantier
    {
        return $this->id_chantier;
    }

    public function setIdChantier(?Chantier $id_chantier): self
    {
        $this->id_chantier = $id_chantier;

        return $this;
    }
}
