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

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTime $start_at = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTime $end_at = null;

    #[ORM\Column(type: Types::TEXT, length: 255)]
    private ?string $objet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $complements = null;

    #[ORM\Column(type: Types::TEXT, length: 255)]
    private ?string $nature = null;

    #[ORM\Column(type: Types::TEXT, length: 7)]
    private ?string $bg_color = null;

    #[ORM\Column(type: Types::TEXT, length: 7)]
    private ?string $bd_color = null;

    #[ORM\Column(type: Types::TEXT, length: 7)]
    private ?string $tx_color = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $all_day = false;

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
        return $this->start_at;
    }

    public function setEndAt(DateTime $start_at): self
    {
        $this->start_at = $start_at;
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

    /**
     * Get the value of bg_color
     */ 
    public function getBgColor(): string
    {
        return $this->bg_color;
    }

    /**
     * Set the value of bg_color
     * @return  self
     */ 
    public function setBgColor(string $bg_color): self
    {
        $this->bg_color = $bg_color;
        return $this;
    }

    /**
     * Get the value of bd_color
     */ 
    public function getBdColor(): string
    {
        return $this->bd_color;
    }

    /**
     * Set the value of bd_color
     * @return  self
     */ 
    public function setBdColor(string $bd_color): self
    {
        $this->bd_color = $bd_color;
        return $this;
    }

    /**
     * Get the value of tx_color
     */ 
    public function getTxColor(): string
    {
        return $this->tx_color;
    }

    /**
     * Set the value of tx_color
     * @return  self
     */ 
    public function setTxColor(string $tx_color): self
    {
        $this->yx_color = $tx_color;
        return $this;
    }

    /**
     * Get the value of all_day
     */ 
    public function getAllDay(): bool
    {
        return $this->all_day;
    }

    /**
     * Set the value of all_day
     *
     * @return  self
     */ 
    public function setAllDay(bool $all_day): self
    {
        $this->all_day = $all_day;

        return $this;
    }
}