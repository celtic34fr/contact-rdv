<?php

namespace Celtic34fr\ContactRendezVous\Entity;

use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactRendezVous\Repository\RendezVousRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
#[ORM\Table('rendezvous')]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?DateTime $time_at = null;

    #[ORM\Column(length: 255)]
    private ?string $objet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $complements = null;

    #[ORM\ManyToOne(inversedBy: 'entretiens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CliInfos $invite = null;

    #[ORM\OneToOne(inversedBy: 'rendezVous')]
    private ?CompteRendu $compte_rendu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeAt(): ?DateTime
    {
        return $this->time_at;
    }

    public function setTimeAt(DateTime $time_at): self
    {
        $this->time_at = $time_at;
        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;
        return $this;
    }

    public function getComplements(): ?string
    {
        return $this->complements;
    }

    public function setComplements(?string $complements): self
    {
        $this->complements = $complements;
        return $this;
    }

    public function getInvite(): ?CliInfos
    {
        return $this->invite;
    }

    public function setInvite(?CliInfos $invite): self
    {
        $this->invite = $invite;
        return $this;
    }

    public function getCompteRendu(): ?CompteRendu
    {
        return $this->compte_rendu;
    }

    public function setCompteRendu(?CompteRendu $compte_rendu): self
    {
        $this->compte_rendu = $compte_rendu;
        return $this;
    }
}