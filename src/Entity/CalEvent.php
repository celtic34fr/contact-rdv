<?php

namespace Celtic34fr\ContactRendezVous\Entity;

use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactCore\Entity\Parameter;
use Celtic34fr\ContactRendezVous\Enum\StatusEnums;
use Celtic34fr\ContactRendezVous\Repository\CalEventRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CalEventRepository::class)]
#[ORM\Table('cal_events')]
class CalEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    #[Assert\DateTime]
    private ?DateTime $created_at;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Assert\DateTime]
    private ?DateTime $last_updated = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Assert\DateTime]
    private ?DateTime $start_at = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Assert\DateTime]
    private ?DateTime $end_at = null;

    #[ORM\Column(type: Types::TEXT, length: 255, nullable: true)]
    private ?string $objet = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $complements = null;

    #[ORM\ManyToOne(targetEntity: Parameter::class)]
    #[ORM\JoinColumn(name: 'nature_id', referencedColumnName: 'id', nullable: true)]
    #[Assert\Type('string')]
    private ?string $nature = null;

    #[ORM\Column(type: Types::TEXT, length: 7)]
    #[Assert\Type('string')]
    #[Assert\Length(
        min: 7,     minMessage: "La taille minimale est de 7 caractères",
        max: 7,     maxMessage: "La taille maximale est de 7 caractères"
    )]
    private ?string $bg_color = null;

    #[ORM\Column(type: Types::TEXT, length: 7)]
    #[Assert\Type('string')]
    #[Assert\Length(
        min: 7,     minMessage: "La taille minimale est de 7 caractères",
        max: 7,     maxMessage: "La taille maximale est de 7 caractères"
    )]
    private ?string $bd_color = null;

    #[ORM\Column(type: Types::TEXT, length: 7)]
    #[Assert\Type('string')]
    #[Assert\Length(
        min: 7,     minMessage: "La taille minimale est de 7 caractères",
        max: 7,     maxMessage: "La taille maximale est de 7 caractères"
    )]
    private ?string $tx_color = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\Type('boolean')]
    private ?bool $all_day = false;

    #[ORM\Column(type: Types::TEXT, length: 4)]
    #[Assert\Type('string')]
    private ?string $status = null;

    #[ORM\ManyToMany(targetEntity: CliInfos::class)]
    #[ORM\JoinColumn(name: 'cliInfos_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\InverseJoinColumn(name: 'calevent_id', referencedColumnName: 'id', unique: true)]
    #[ORM\JoinTable(name: 'cliinfos_calevents')]
    #[Assert\Type(CliInfos::class)]
    private ?CliInfos $invite = null;

    #[ORM\OneToOne(targetEntity: CompteRendu::class, inversedBy: 'rendezVous')]
    #[Assert\Type(CompteRendu::class)]
    private ?CompteRendu $compte_rendu = null;

    #[ORM\Column(type: Types::TEXT, length: 255, nullable: true)]
    #[Assert\Type('string')]
    private ?string $uid = null;


    public function __construct()
    {
        $this->setStatus(StatusEnums::WaitResponse->_toString());
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     */
    public function setCreatedAt(DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getLastUpdated(): ?DateTime
    {
        return $this->last_updated;
    }

    public function setLastUpdated(?DateTime $last_updated): self
    {
        $this->last_updated = $last_updated;
        return $this;
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


    public function getNature(): ?Parameter
    {
        return $this->nature;
    }

    public function setNature(Parameter $nature): self
    {
        $this->nature = $nature;
        return $this;
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
        $this->tx_color = $tx_color;
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

    /**
     * Get the value of status of the event
     * 
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     * 
     * @param string $status
     * @return CalEvent|bool
     */
    public function setStatus(string $status): mixed
    {
        if (StatusEnums::isValid($status)) {
            $this->status = $status;
            return $this;
        }
        return false;
    }

    /**
     * Get the value of uid
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * Set the value of uid
     */
    public function setUid(?string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }
}