<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlatRepository::class)
 */
class Plat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Libelle;

    /**
     * @ORM\Column(type="array")
     */
    private $Ingredients = [];

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="plats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Categorie;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $Allergene = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Position;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $Regime = [];

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $Image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getIngredients(): ?array
    {
        return $this->Ingredients;
    }

    public function setIngredients(array $Ingredients): self
    {
        $this->Ingredients = $Ingredients;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getAllergene(): ?array
    {
        return $this->Allergene;
    }

    public function setAllergene(?array $Allergene): self
    {
        $this->Allergene = $Allergene;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->Position;
    }

    public function setPosition(?int $Position): self
    {
        $this->Position = $Position;

        return $this;
    }

    public function getRegime(): ?array
    {
        return $this->Regime;
    }

    public function setRegime(?array $Regime): self
    {
        $this->Regime = $Regime;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }
}
