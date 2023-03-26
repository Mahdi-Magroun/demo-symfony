<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use SSH\MyJwtBundle\Utils\MyTools;
use App\Repository\NoticeRepository;

#[ORM\Entity(repositoryClass: NoticeRepository::class)]
class Notice
{
    #[ORM\Id]
    #[ORM\GeneratedValue (strategy:'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'notices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Debt $debt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?MunicipalityAgent $creator = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\ManyToOne]
    private ?MunicipalityAgent $updator = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $sendedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $recivedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transferType = null;

    private ?array $allowedTransfertType=[
        'e-mail',
        'post'
    ];

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

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

    public function getDebt(): ?Debt
    {
        return $this->debt;
    }

    public function setDebt(?Debt $debt): self
    {
        $this->debt = $debt;

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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSendedAt(): ?\DateTime
    {
        return $this->sendedAt;
    }

    public function setSendedAt(?\DateTime $sendedAt): self
    {
        $this->sendedAt = $sendedAt;

        return $this;
    }

    public function getRecivedAt(): ?\DateTime
    {
        return $this->recivedAt;
    }

    public function setRecivedAt(?\DateTime $recivedAt): self
    {
        $this->recivedAt = $recivedAt;

        return $this;
    }

    public function getTransferType(): ?string
    {

        return json_decode( $this->transferType);
    }

    public function setTransferType(?array $transferType): self
    {
        if (in_array($transferType,$this->allowedTransfertType)) {
            # code...
            $this->transferType = $transferType;

            return $this;
        }
        throw new \Exception("Unknown_transfert_type", 1);
        
       
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
