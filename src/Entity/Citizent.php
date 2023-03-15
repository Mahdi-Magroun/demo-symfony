<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SSH\MyJwtBundle\Utils\MyTools;
use App\Repository\CitizentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CitizentRepository::class)]
class Citizent
{
    #[ORM\Id]
    #[ORM\GeneratedValue (strategy:'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    private ?string $lastName = null;

    #[ORM\Column(length: 50)]
    private ?string $fatherName = null;

    #[ORM\Column(length: 50)]
    private ?string $garndFatherName = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $identityCard = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $passport = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $residenceCard = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $idType = null;

    #[ORM\ManyToMany(targetEntity: Municipality::class, inversedBy: 'citizents')]
    private Collection $municipalitys;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Property::class)]
    private Collection $properties;

    #[ORM\Column]
    private ?int $zipCode = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column]
    private ?int $buildingNumber = null;

    #[ORM\OneToMany(mappedBy: 'citizent', targetEntity: Debt::class)]
    private Collection $debts;

    public function __construct()
    {
        $this->municipalitys = new ArrayCollection();
        $this->properties = new ArrayCollection();
        $this->code = MyTools::GUIDv4();
        $this->debts = new ArrayCollection();
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFatherName(): ?string
    {
        return $this->fatherName;
    }

    public function setFatherName(string $fatherName): self
    {
        $this->fatherName = $fatherName;

        return $this;
    }

    public function getGarndFatherName(): ?string
    {
        return $this->garndFatherName;
    }

    public function setGarndFatherName(string $garndFatherName): self
    {
        $this->garndFatherName = $garndFatherName;

        return $this;
    }

    public function getIdentityCard(): ?string
    {
        return $this->identityCard;
    }

    public function setIdentityCard(?string $identityCard): self
    {
        $this->identityCard = $identityCard;

        return $this;
    }

    public function getPassport(): ?string
    {
        return $this->passport;
    }

    public function setPassport(?string $passport): self
    {
        $this->passport = $passport;

        return $this;
    }

    public function getResidenceCard(): ?string
    {
        return $this->residenceCard;
    }

    public function setResidenceCard(?string $residenceCard): self
    {
        $this->residenceCard = $residenceCard;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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

    public function getIdType(): ?string
    {
        return $this->idType;
    }

    public function setIdType(string $idType): self
    {
        $allowedIdType = ['passport',"identityCard","residenceCaed"];
        if (in_array($idType,$allowedIdType)) {
            # code...
            $this->idType = $idType;
        }
        else {
            throw new \Exception("idType_not_allowed    ", 1);
            
        }
        

        return $this;
    }

    /**
     * @return Collection<int, Municipality>
     */
    public function getMunicipalitys(): Collection
    {
        return $this->municipalitys;
    }

    public function addMunicipality(Municipality $municipality): self
    {
        if (!$this->municipalitys->contains($municipality)) {
            $this->municipalitys->add($municipality);
        }

        return $this;
    }

    public function removeMunicipality(Municipality $municipality): self
    {
        $this->municipalitys->removeElement($municipality);

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
            $property->setOwner($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->removeElement($property)) {
            // set the owning side to null (unless already changed)
            if ($property->getOwner() === $this) {
                $property->setOwner(null);
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

    /**
     * @return Collection<int, Debt>
     */
    public function getDebts(): Collection
    {
        return $this->debts;
    }

    public function addDebt(Debt $debt): self
    {
        if (!$this->debts->contains($debt)) {
            $this->debts->add($debt);
            $debt->setCitizent($this);
        }

        return $this;
    }

    public function removeDebt(Debt $debt): self
    {
        if ($this->debts->removeElement($debt)) {
            // set the owning side to null (unless already changed)
            if ($debt->getCitizent() === $this) {
                $debt->setCitizent(null);
            }
        }

        return $this;
    }


}
