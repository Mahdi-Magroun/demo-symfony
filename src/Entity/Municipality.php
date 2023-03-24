<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SSH\MyJwtBundle\Utils\MyTools;
use App\Repository\MunicipalityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MunicipalityRepository::class)]
class Municipality
{
    #[ORM\Id]
    #[ORM\GeneratedValue (strategy:'IDENTITY')]  
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 100,unique:true)]
    private ?string $frenshName = null;

    #[ORM\Column(length: 100,unique:true)]
    private ?string $arabicName = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $webSite = null;

    #[ORM\Column]
    private ?int $nationalId = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $populationCount = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?int $yearPopulationCount = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable:true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $isActivated = null;

    #[ORM\ManyToOne(inversedBy: 'municipalities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Governorate $governorate = null;

    #[ORM\OneToMany(mappedBy: 'municipality', targetEntity: MunicipalityAgent::class)]
    private Collection $municipalityAgents;

    
    #[ORM\OneToMany(mappedBy: 'municipality', targetEntity: Property::class)]
    private Collection $properties;

    #[ORM\Column]
    private ?int $zipCode = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column]
    private ?int $buildingNumber = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $email = null;

    #[ORM\ManyToOne]
    private ?Team $creator = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Team $updator = null;

    public function __construct()
    {
        $this->municipalityAgents = new ArrayCollection();
     
        $this->properties = new ArrayCollection();
        $this->code = MyTools::GUIDv4();
       
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFrenshName(): ?string
    {
        return $this->frenshName;
    }

    public function setFrenshName(string $frenshName): self
    {
        $this->frenshName = $frenshName;

        return $this;
    }

    public function getArabicName(): ?string
    {
        return $this->arabicName;
    }

    public function setArabicName(string $arabicName): self
    {
        $this->arabicName = $arabicName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    public function setWebSite(?string $webSite): self
    {
        $this->webSite = $webSite;

        return $this;
    }

    public function getNationalId(): ?int
    {
        return $this->nationalId;
    }

    public function setNationalId(int $nationalId): self
    {
        $this->nationalId = $nationalId;

        return $this;
    }

    public function getPopulationCount(): ?string
    {
        return $this->populationCount;
    }

    public function setPopulationCount(?string $populationCount): self
    {
        $this->populationCount = $populationCount;

        return $this;
    }

    public function getYearPopulationCount(): ?string
    {
        return $this->yearPopulationCount;
    }

    public function setYearPopulationCount(string $yearPopulationCount): self
    {
        $this->yearPopulationCount = $yearPopulationCount;

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

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
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

    public function getGovernorate(): ?Governorate
    {
        return $this->governorate;
    }

    public function setGovernorate(?Governorate $governorate): self
    {
        $this->governorate = $governorate;

        return $this;
    }

    /**
     * @return Collection<int, MunicipalityAgent>
     */
    public function getMunicipalityAgents(): Collection
    {
        return $this->municipalityAgents;
    }

    public function addMunicipalityAgent(MunicipalityAgent $municipalityAgent): self
    {
        if (!$this->municipalityAgents->contains($municipalityAgent)) {
            $this->municipalityAgents->add($municipalityAgent);
            $municipalityAgent->setMunicipality($this);
        }

        return $this;
    }

    public function removeMunicipalityAgent(MunicipalityAgent $municipalityAgent): self
    {
        if ($this->municipalityAgents->removeElement($municipalityAgent)) {
            // set the owning side to null (unless already changed)
            if ($municipalityAgent->getMunicipality() === $this) {
                $municipalityAgent->setMunicipality(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Property>
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
            $property->setMunicipality($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getMunicipality() === $this) {
                $property->setMunicipality(null);
            }
        }

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getBuildingNumber(): ?int
    {
        return $this->buildingNumber;
    }

    public function setBuildingNumber(int $buildingNumber): self
    {
        $this->buildingNumber = $buildingNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

}
