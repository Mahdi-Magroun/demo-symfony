<?php

namespace App\Entity;


use SSH\MyJwtBundle\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use SSH\MyJwtBundle\Utils\MyTools;

use App\Repository\GovernorateRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: GovernorateRepository::class)]
class Governorate extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue (strategy:'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_municipality'])]
    private ?string $code = null;

    #[ORM\Column]
    #[Groups(['show_municipality'])]
    private ?int $nationalId = null;

    #[ORM\Column(length: 100)]
    #[Groups(['show_municipality'])]
    private ?string $frenshName = null;

    #[ORM\Column(length: 100)]
    #[Groups(['show_municipality'])]
    private ?string $arabicName = null;

    #[ORM\OneToMany(mappedBy: 'governorate', targetEntity: Municipality::class)]
    private Collection $municipalities;

    public function __construct()
    {
        $this->municipalities = new ArrayCollection();
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

    public function getNationalId(): ?int
    {
        return $this->nationalId;
    }

    public function setNationalId(int $nationalId): self
    {
        $this->nationalId = $nationalId;

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

    /**
     * @return Collection<int, Municipality>
     */
    public function getMunicipalities(): Collection
    {
        return $this->municipalities;
    }

    public function addMunicipality(Municipality $municipality): self
    {
        if (!$this->municipalities->contains($municipality)) {
            $this->municipalities->add($municipality);
            $municipality->setGovernorate($this);
        }

        return $this;
    }

    public function removeMunicipality(Municipality $municipality): self
    {
        if ($this->municipalities->removeElement($municipality)) {
            // set the owning side to null (unless already changed)
            if ($municipality->getGovernorate() === $this) {
                $municipality->setGovernorate(null);
            }
        }

        return $this;
    }
}
