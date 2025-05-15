<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $categorie = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $image1;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $image2;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $image3;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, Panier>
     */
    #[ORM\ManyToMany(targetEntity: Panier::class, mappedBy: 'produit')]
    private Collection $paniers;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'produit')]
    private Collection $commandes;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getImage1()
    {
        return $this->image1;
    }

    public function setImage1($image1): static
    {
        $this->image1 = $image1;

        return $this;
    }

    public function getImage2()
    {
        return $this->image2;
    }

    public function setImage2($image2): static
    {
        $this->image2 = $image2;

        return $this;
    }

    public function getImage3()
    {
        return $this->image3;
    }

    public function setImage3($image3): static
    {
        $this->image3 = $image3;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): static
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->addProduit($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->paniers->removeElement($panier)) {
            $panier->removeProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->addProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            $commande->removeProduit($this);
        }

        return $this;
    }


}
