<?php

namespace App\Entity;

use App\Repository\CategoriecodepromoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoriecodepromoRepository::class)]
class Categoriecodepromo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column (type: "float", name: "pourcentage")]
    #[Assert\Type(type: "float", message: "The value {{ value }} is not a valid float.")]
    #[Assert\Positive(message: "The value must be positive.")]
    #[Assert\NotBlank(message: "The value cannot be void.")]
    private ?float $pourcentage = null;

    #[ORM\Column( name: "clé" ,length: 11)]
    #[Assert\Length(max: 10, maxMessage: "The key cannot be longer than {{ limit }} characters.")]
    #[Assert\Length(min: 5, minMessage: "The key cannot be less than {{ limit }} characters.")]
    #[Assert\NotBlank(message: "The value cannot be blank.")]
    private ?string $cle = null;

    #[ORM\Column ( name: "quantité")]
    #[Assert\Type(type: "integer", message: "The value {{ value }} is not a valid integer.")]
    #[Assert\Positive(message: "The value must be positive.")]
    #[Assert\NotBlank(message: "The value cannot be blank.")]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPourcentage(): ?float
    {
        return $this->pourcentage;
    }

    public function setPourcentage(float $pourcentage): static
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function getCle(): ?string
    {
        return $this->cle;
    }

    public function setCle(string $cle): static
    {
        $this->cle = $cle;

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
}
