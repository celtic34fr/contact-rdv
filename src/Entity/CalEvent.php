<?php

namespace Celtic34fr\ContactRendezVous\Entity;

use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactCore\Enum\EventEnums;
use Celtic34fr\ContactRendezVous\Repository\CalEventRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalEventRepository::class)]
#[ORM\Table('cal_events')]
class CalEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $time_at = null;

    #[ORM\Column(type: Types::TEXT, length: 255)]
    private ?string $objet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $complements = null;

    #[ORM\Column(type: Types::TEXT, length: 255)]
    private ?string $nature = null;

    #[ORM\ManyToMany(targetEntity: CliInfos::class)]
    #[ORM\JoinColumn(name: 'cliInfos_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\InverseJoinColumn(name: 'calevent_id', referencedColumnName: 'id', unique: true)]
    #[ORM\JoinTable(name: 'cliinfos_calecents')]
    private ?CliInfos $invite = null;

    #[ORM\OneToOne(targetEntity: CompteRendu::class, inversedBy: 'rendezVous')]
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


    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(string $nature): bool|self
    {
        if (EventEnums::isValid($nature)) {
            $this->nature = $nature;
            return $this;
        }
        return false;
    }
}