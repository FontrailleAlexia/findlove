<?php

namespace App\Entity;

use App\Repository\CriteriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CriteriaRepository::class)]
class Criteria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $silhouete = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alcohol = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tobacco = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eyes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hair = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToMany(targetEntity: ParticularSign::class, inversedBy: 'criterias')]
    private Collection $particularSigns;

    #[ORM\ManyToMany(targetEntity: Origin::class, inversedBy: 'criterias')]
    private Collection $Origins;

    #[ORM\ManyToMany(targetEntity: Style::class, inversedBy: 'criterias')]
    private Collection $Styles;

    #[ORM\ManyToMany(targetEntity: Hobbies::class, inversedBy: 'criterias')]
    private Collection $hobbies;

    public function __construct()
    {
        $this->particularSigns = new ArrayCollection();
        $this->Origins = new ArrayCollection();
        $this->Styles = new ArrayCollection();
        $this->hobbies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getSilhouete(): ?string
    {
        return $this->silhouete;
    }

    public function setSilhouete(?string $silhouete): self
    {
        $this->silhouete = $silhouete;

        return $this;
    }

    public function getAlcohol(): ?string
    {
        return $this->alcohol;
    }

    public function setAlcohol(?string $alcohol): self
    {
        $this->alcohol = $alcohol;

        return $this;
    }

    public function getTobacco(): ?string
    {
        return $this->tobacco;
    }

    public function setTobacco(?string $tobacco): self
    {
        $this->tobacco = $tobacco;

        return $this;
    }

    public function getEyes(): ?string
    {
        return $this->eyes;
    }

    public function setEyes(?string $eyes): self
    {
        $this->eyes = $eyes;

        return $this;
    }

    public function getHair(): ?string
    {
        return $this->hair;
    }

    public function setHair(?string $hair): self
    {
        $this->hair = $hair;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, ParticularSign>
     */
    public function getParticularSigns(): Collection
    {
        return $this->particularSigns;
    }

    public function addParticularSign(ParticularSign $particularSign): self
    {
        if (!$this->particularSigns->contains($particularSign)) {
            $this->particularSigns->add($particularSign);
        }

        return $this;
    }

    public function removeParticularSign(ParticularSign $particularSign): self
    {
        $this->particularSigns->removeElement($particularSign);

        return $this;
    }

    /**
     * @return Collection<int, Origin>
     */
    public function getOrigins(): Collection
    {
        return $this->Origins;
    }

    public function addOrigin(Origin $origin): self
    {
        if (!$this->Origins->contains($origin)) {
            $this->Origins->add($origin);
        }

        return $this;
    }

    public function removeOrigin(Origin $origin): self
    {
        $this->Origins->removeElement($origin);

        return $this;
    }

    /**
     * @return Collection<int, Style>
     */
    public function getStyles(): Collection
    {
        return $this->Styles;
    }

    public function addStyle(Style $style): self
    {
        if (!$this->Styles->contains($style)) {
            $this->Styles->add($style);
        }

        return $this;
    }

    public function removeStyle(Style $style): self
    {
        $this->Styles->removeElement($style);

        return $this;
    }

    /**
     * @return Collection<int, Hobbies>
     */
    public function getHobbies(): Collection
    {
        return $this->hobbies;
    }

    public function addHobby(Hobbies $hobby): self
    {
        if (!$this->hobbies->contains($hobby)) {
            $this->hobbies->add($hobby);
        }

        return $this;
    }

    public function removeHobby(Hobbies $hobby): self
    {
        $this->hobbies->removeElement($hobby);

        return $this;
    }
}
