<?php

namespace Celtic34fr\ContactRendezVous\Entity;

use Celtic34fr\ContactCore\Entity\CliInfos;
use Celtic34fr\ContactRendezVous\Repository\CompteRenduRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteRenduRepository::class)]
#[ORM\Table('compte_rendu')]
class CompteRendu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true, type: Types::DATE_IMMUTABLE)]
    private ?DateTime $write_at = null;

    #[ORM\Column(nullable: true, type: Types::INTEGER)]
    private ?int $duree = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $compte_rendu = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CliInfos $invite = null;

    #[ORM\OneToOne(mappedBy: 'compte_rendu')]
    #[ORM\JoinColumn(nullable: true)]
    private ?RendezVous $rendezVous = null;


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

    public function getRendezVous(): ?RendezVous
    {
        return $this->rendezVous;
    }

    public function setRendezVous(?RendezVous $rendezVous): self
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
