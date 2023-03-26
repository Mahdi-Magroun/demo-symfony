<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaxeRepository;
use SSH\MyJwtBundle\Utils\MyTools;
use SSH\MyJwtBundle\Entity\AbstractEntity;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TaxeRepository::class)]
class Taxe extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue (strategy:'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255 , unique:true)]
    #[Groups(['show_taxe'])]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['show_taxe'])]
    private ?string $abbreviation = null;

    #[ORM\Column]
    #[Groups(['show_taxe'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_taxe'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column]
    #[Groups(['show_taxe'])]
    private ?bool $isActivated = null;

    #[ORM\Column()]
    #[Groups(['show_taxe'])]
    private ?\DateTime $dateBegin = null;

    #[ORM\Column( nullable: true)]
    #[Groups(['show_taxe'])]
    private ?\DateTime $dateEnd = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show_taxe'])]
    private ?Team $creator = null;

    #[ORM\ManyToOne]
    #[Groups(['show_taxe'])]
    private ?Team $updator = null;

    #[ORM\OneToMany(mappedBy: 'taxe', targetEntity: TaxeSearchCriteria::class)]
    private Collection $taxeSearchCriterias;

    #[ORM\Column(length: 255)]
    #[Groups(['show_taxe'])]
    private ?string $code = null;

    public function __construct($data=[])
    {
        parent::__construct($data);
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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getDateBegin(): ?\DateTime
    {
        return $this->dateBegin;
    }

    public function setDateBegin(\DateTime $dateBegin): self
    {
        $this->dateBegin = $dateBegin;

        return $this;
    }

    public function getDateEnd(): ?\DateTime
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTime $dateEnd): self
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
