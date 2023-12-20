<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $id_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $reference_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_produit = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $image_produit = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $mots_cles = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $note_nutriscore = null;

    #[ORM\Column(nullable: true)]
    private ?int $energie = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_derniere_maj = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProduit(): ?string
    {
        return $this->id_produit;
    }

    public function setIdProduit(string $id_produit): static
    {
        $this->id_produit = $id_produit;

        return $this;
    }

    public function getReferenceProduit(): ?string
    {
        return $this->reference_produit;
    }

    public function setReferenceProduit(string $reference_produit): static
    {
        $this->reference_produit = $reference_produit;

        return $this;
    }

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): static
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getDescriptionProduit(): ?string
    {
        return $this->description_produit;
    }

    public function setDescriptionProduit(string $description_produit): static
    {
        $this->description_produit = $description_produit;

        return $this;
    }

    public function getImageProduit(): ?string
    {
        return $this->image_produit;
    }

    public function setImageProduit(string $image_produit): static
    {
        $this->image_produit = $image_produit;

        return $this;
    }

    public function getMotsCles(): ?string
    {
        return $this->mots_cles;
    }

    public function setMotsCles(string $mots_cles): static
    {
        $this->mots_cles = $mots_cles;

        return $this;
    }

    public function getNoteNutriscore(): ?string
    {
        return $this->note_nutriscore;
    }

    public function setNoteNutriscore(?string $note_nutriscore): static
    {
        $this->note_nutriscore = $note_nutriscore;

        return $this;
    }

    public function getEnergie(): ?int
    {
        return $this->energie;
    }

    public function setEnergie(?int $energie): static
    {
        $this->energie = $energie;

        return $this;
    }

    public function getDateDerniereMaj(): ?\DateTimeInterface
    {
        return $this->date_derniere_maj;
    }

    public function setDateDerniereMaj(?\DateTimeInterface $date_derniere_maj): static
    {
        $this->date_derniere_maj = $date_derniere_maj;

        return $this;
    }
}
