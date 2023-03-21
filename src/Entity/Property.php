<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SSH\MyJwtBundle\Utils\MyTools;
use App\Repository\PropertyRepository;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue (strategy:'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Citizent $owner = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    private ?Municipality $municipality = null;

    #[ORM\Column]
    private ?int $zipCode = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column]
    private ?int $buildingNumber = null;

    #[ORM\Column(nullable: true)]
    private ?float $surface = null;

    #[ORM\OneToOne(mappedBy: 'property', cascade: ['persist', 'remove'])]
    private ?Debt $debt = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    private array $allowedTypes = [
        'building',
        'land'
    ];

    public function __construct()
    {
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

    public function getOwner(): ?Citizent
    {
        return $this->owner;
    }

    public function setOwner(?Citizent $owner): self
    {
        $this->owner = $owner;

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

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(?float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getDebt(): ?Debt
    {
        return $this->debt;
    }

    public function setDebt(Debt $debt): self
    {
        // set the owning side of the relation if necessary
        if ($debt->getProperty() !== $this) {
            $debt->setProperty($this);
        }

        $this->debt = $debt;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        if (in_array($type,$this->allowedTypes)) {
            # code...
            $this->type = $type;

            return $this;
        }
        throw new \Exception("property type not allowed ", 1);
        
       
    }
}
