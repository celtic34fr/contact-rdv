<?php

namespace Celtic34fr\ContactRendezVous\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactRendezVous\Entity\CompteRendu;
use Celtic34fr\ContactRendezVous\Enum\RendezVousEnums;
use Celtic34fr\ContactRendezVous\Repository\RendezVousRepository;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
#[ORM\Table('rendezvous')]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?DateTime $start_at = null;

    #[ORM\Column]
    private ?DateTime $end_at = null;

    #[ORM\Column(length: 255)]
    private ?string $objet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $complements = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    private $all_day;

    #[ORM\Column(type: 'string', nullable: false)]
    private ?string $nature = null;                     // nature, type du rendez-vous, cf Enum\RendezVousEnumes

    #[ORM\ManyToOne(inversedBy: 'entretiens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CliInfos $invite = null;

    #[ORM\OneToOne(inversedBy: 'rendezVous')]
    #[ORM\JoinColumn(nullable: true)]
    private ?CompteRendu $compte_rendu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?DateTime
    {
        return $this->start_at;
    }

    public function setStartAt(DateTime $start_at): self
    {
        $this->start_at = $start_at;
        return $this;
    }

    public function getEndAt(): ?DateTime
    {
        return $this->end_at;
    }

    public function setEndAt(DateTime $end_at): self
    {
        $this->end_at = $end_at;
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

    public function getAllDay()
    {
        return (bool) $this->all_day;
    }

    public function setAllDay(bool $all_day): self
    {
        $this->all_day = $all_day;
        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): bool|self
    {
        if (RendezVousEnums::isValid($nature)) {
            $this->nature = $nature;
            return $this;
        }
        return false;
    }
}
