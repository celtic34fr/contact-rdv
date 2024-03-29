<?php

namespace Celtic34fr\ContactRendezVous\Entity;

use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactRendezVous\Repository\CompteRenduRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompteRenduRepository::class)]
#[ORM\Table('comptes_rendus')]
class CompteRendu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\DateTime]
    private ?DateTime $write_at = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $duree = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $compte_rendu = null;

    #[ORM\ManyToMany(targetEntity: CliInfos::class)]
    #[ORM\JoinColumn(name: 'compoterendu_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\InverseJoinColumn(name: 'cliinfos_id', referencedColumnName: 'id', unique: true)]
    #[ORM\JoinTable(name: 'cliinfos_compterendu')]
    private ?CliInfos $invite = null;

    #[ORM\OneToOne(targetEntity: EventRdv::class, mappedBy: 'compte_rendu')]
    private ?EventRdv $rendezVous = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWriteAt(): ?DateTime
    {
        return $this->write_at;
    }

    public function setWriteAt(DateTime $write_at): self
    {
        $this->write_at = $write_at;
        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    public function getCompteRendu(): ?string
    {
        return $this->compte_rendu;
    }

    public function setCompteRendu(string $compte_rendu): self
    {
        $this->compte_rendu = $compte_rendu;
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

    public function getRendezVous(): ?EventRdv
    {
        return $this->rendezVous;
    }

    public function setRendezVous(?EventRdv $rendezVous): self
    {
        // unset the owning side of the relation if necessary
        if ($rendezVous === null && $this->rendezVous !== null) {
            $this->rendezVous->setCompteRendu(null);
        }
        // set the owning side of the relation if necessary
        if ($rendezVous !== null && $rendezVous->getCompteRendu() !== $this) {
            $rendezVous->setCompteRendu($this);
        }
        $this->rendezVous = $rendezVous;
        return $this;
    }
}