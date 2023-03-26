<?php

namespace App\Entity;

use SSH\MyJwtBundle\Entity\AbstractEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SSH\MyJwtBundle\Utils\MyTools;
use App\Repository\MunicipalityAgentRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MunicipalityAgentRepository::class)]
class MunicipalityAgent extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue (strategy:'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_no_credentials','president_details'])]
    private ?string $code = null;

    #[ORM\Column(length: 50)]
    #[Groups(['show_no_credentials','president_details'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    #[Groups(['show_no_credentials','president_details'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 50,unique:true)]
    #[Groups(['show_no_credentials','president_details'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Groups(['president_details'])]
    private ?string $cin = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $role = null;

    #[ORM\Column] 
    #[Groups(['president_details'])]
    private ?bool $isActivated = null;

    #[ORM\ManyToOne(inversedBy: 'municipalityAgents')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['president_details'])]
    private ?Municipality $municipality = null;

   

    #[ORM\Column()]
    #[Groups(['show_no_credentials','president_details'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_no_credentials','president_details'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(nullable:true)]
    #[Groups(['show_no_credentials','president_details'])]
    private ?\DateTime $dateBegin = null;

    #[ORM\Column( nullable: true)]
    #[Groups(['show_no_credentials','president_details'])]
    private ?\DateTime $dateEnd = null;

    public function __construct($data=[])
    {
        parent::__construct($data);
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    
    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

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

    public function getMunicipality(): ?Municipality
    {
        return $this->municipality;
    }

    public function setMunicipality(?Municipality $municipality): self
    {
        $this->municipality = $municipality;

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
}
