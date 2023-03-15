<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaxeRepository;
use SSH\MyJwtBundle\Utils\MyTools;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: TaxeRepository::class)]
class Taxe
{
    #[ORM\Id]
    #[ORM\GeneratedValue (strategy:'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $isActivated = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateBegin = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateEnd = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $creator = null;

    #[ORM\ManyToOne]
    private ?Team $updator = null;

    #[ORM\OneToMany(mappedBy: 'taxe', targetEntity: TaxeSearchCriteria::class)]
    private Collection $taxeSearchCriterias;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    public function __construct()
    {
        $this->taxeSearchCriterias = new ArrayCollection();
        $this->code = MyTools::GUIDv4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getDateBegin(): ?\DateTimeImmutable
    {
        return $this->dateBegin;
    }

    public function setDateBegin(\DateTimeImmutable $dateBegin): self
    {
        $this->dateBegin = $dateBegin;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeImmutable
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeImmutable $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getCreator(): ?Team
    {
        return $this->creator;
    }

    public function setCreator(?Team $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getUpdator(): ?Team
    {
        return $this->updator;
    }

    public function setUpdator(?Team $updator): self
    {
        $this->updator = $updator;

        return $this;
    }

    /**
     * @return Collection<int, TaxeSearchCriteria>
     */
    public function getTaxeSearchCriterias(): Collection
    {
        return $this->taxeSearchCriterias;
    }

    public function addTaxeSearchCriteria(TaxeSearchCriteria $taxeSearchCriteria): self
    {
        if (!$this->taxeSearchCriterias->contains($taxeSearchCriteria)) {
            $this->taxeSearchCriterias->add($taxeSearchCriteria);
            $taxeSearchCriteria->setTaxe($this);
        }

        return $this;
    }

    public function removeTaxeSearchCriteria(TaxeSearchCriteria $taxeSearchCriteria): self
    {
        if ($this->taxeSearchCriterias->removeElement($taxeSearchCriteria)) {
            // set the owning side to null (unless already changed)
            if ($taxeSearchCriteria->getTaxe() === $this) {
                $taxeSearchCriteria->setTaxe(null);
            }
        }

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
}
