<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlatRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlatRepository::class)
 */
class Plat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("category:read")
     * @Groups("plat:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, max=255)
     * @Groups("category:read")
     * @Groups("plat:read")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, max=255)
     * @Groups("category:read")
     * @Groups("plat:read")
     */
    private $ingredients;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="plats", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups("plat:read")
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     * @Groups("category:read")
     * @Groups("plat:read")
     */
    private $allergene;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     * @Groups("category:read")
     * @Groups("plat:read")
     */
    private $regime;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Assert\Url()
     * @Groups("category:read")
     * @Groups("plat:read")
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getCategorie(): ?categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getAllergene(): ?string
    {
        return $this->allergene;
    }

    public function setAllergene(?string $allergene): self
    {
        $this->allergene = $allergene;

        return $this;
    }

    public function getRegime(): ?string
    {
        return $this->regime;
    }

    public function setRegime(?string $regime): self
    {
        $this->regime = $regime;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
