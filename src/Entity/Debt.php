<?php

namespace App\Entity;

use App\Repository\DebtRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DebtRepository::class)]
class Debt
{
    #[ORM\Id]
    #[ORM\GeneratedValue (strategy:'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $mainAmount = null;

    #[ORM\Column]
    private ?float $followinfPenaltyAmount = null;

    #[ORM\Column]
    private ?float $latencyPenaltyAmount = null;

    #[ORM\Column]
    private ?float $totalAmount = null;

    #[ORM\OneToOne(inversedBy: 'debt', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Property $property = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Taxe $taxe = null;

    #[ORM\ManyToOne(inversedBy: 'debts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Citizent $citizent = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Municipality $municipality = null;

    #[ORM\ManyToOne]
    private ?MunicipalityAgent $updator = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?MunicipalityAgent $creator = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'debt', targetEntity: Notice::class)]
    private Collection $notices;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    public function __construct()
    {
        $this->notices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainAmount(): ?float
    {
        return $this->mainAmount;
    }

    public function setMainAmount(float $mainAmount): self
    {
        $this->mainAmount = $mainAmount;

        return $this;
    }

    public function getFollowinfPenaltyAmount(): ?float
    {
        return $this->followinfPenaltyAmount;
    }

    public function setFollowinfPenaltyAmount(float $followinfPenaltyAmount): self
    {
        $this->followinfPenaltyAmount = $followinfPenaltyAmount;

        return $this;
    }

    public function getLatencyPenaltyAmount(): ?float
    {
        return $this->latencyPenaltyAmount;
    }

    public function setLatencyPenaltyAmount(float $latencyPenaltyAmount): self
    {
        $this->latencyPenaltyAmount = $latencyPenaltyAmount;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getTaxe(): ?Taxe
    {
        return $this->taxe;
    }

    public function setTaxe(?Taxe $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    public function getCitizent(): ?Citizent
    {
        return $this->citizent;
    }

    public function setCitizent(?Citizent $citizent): self
    {
        $this->citizent = $citizent;

        return $this;
    }

    public function getMunicipality(): ?Municipality
    {
        return $this->municipality;
    }

    public function setMunicipality(?Municipality $municipality): self
    {
        $this->municipality = $municipality;

        return $this;
    }

    public function getUpdator(): ?MunicipalityAgent
    {
        return $this->updator;
    }

    public function setUpdator(?MunicipalityAgent $updator): self
    {
        $this->updator = $updator;

        return $this;
    }

    public function getCreator(): ?MunicipalityAgent
    {
        return $this->creator;
    }

    public function setCreator(?MunicipalityAgent $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Notice>
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

    public function addNotice(Notice $notice): self
    {
        if (!$this->notices->contains($notice)) {
            $this->notices->add($notice);
            $notice->setDebt($this);
        }

        return $this;
    }

    public function removeNotice(Notice $notice): self
    {
        if ($this->notices->removeElement($notice)) {
            // set the owning side to null (unless already changed)
            if ($notice->getDebt() === $this) {
                $notice->setDebt(null);
            }
        }

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
