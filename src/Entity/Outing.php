<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OutingRepository::class)]
class Outing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\GreaterThan(
        'today',
        message: "La date de début doit être postérieure à la date actuelle."
    )]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Expression(
        "this.getClosingDate() < this.getStartDate()",
        message: "La date de clôture doit être antérieure à la date de début."
    )]
    private ?\DateTimeInterface $closingDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;

    #[ORM\ManyToOne(inversedBy: 'outings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organizer = null;

    #[ORM\ManyToOne(inversedBy: 'outings')]
    private ?Status $status = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'outings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    #[ORM\OneToMany(mappedBy: 'outing', targetEntity: Registration::class, cascade: ['remove'])]
    private Collection $registrations;

    #[ORM\Column]
    private ?int $registrationsMax = null;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getClosingDate(): ?\DateTimeInterface
    {
        return $this->closingDate;
    }

    public function setClosingDate(\DateTimeInterface $closingDate): static
    {
        $this->closingDate = $closingDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): static
    {
        $this->poster = $poster;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): static
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): static
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations->add($registration);
            $registration->setOuting($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): static
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getOuting() === $this) {
                $registration->setOuting(null);
            }
        }

        return $this;
    }

    /**
     * Indique si n'importe quel utilisateur peut encore s'inscrire à la sortie
     * @return bool
     */
    public function canRegister(): bool
    {
        return $this->getStartDate() > new \DateTime('now') && $this->getClosingDate() > new \DateTime('now') && $this->getRegistrationsMax() > $this->getRegistrations()->count();
    }

    public function isUserRegistered(?User $user): bool
    {
        if($user){
            foreach ($this->getRegistrations() as $registration) {
                if ($registration->getParticipant() === $user) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Indique si l'utilisateur peut s'inscrire à la sortie
     * @param User|null $user
     * @return bool
     */
    public function canUserRegister(?User $user): bool
    {
        return $user && $this->canRegister() && !$this->isUserRegistered($user) && $this->getOrganizer() !== $user;
    }


    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getLatitude(): ?float
    {
        return $this->place->getLatitude();
    }

    public function getLongitude(): ?float
    {
        return $this->place->getLongitude();
    }

    public function getRegistrationsMax(): ?int
    {
        return $this->registrationsMax;
    }

    public function setRegistrationsMax(int $registrationsMax): static
    {
        $this->registrationsMax = $registrationsMax;

        return $this;
    }
}
