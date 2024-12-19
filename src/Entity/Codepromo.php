<?php

namespace App\Entity;

use App\Repository\CodepromoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CodepromoRepository::class)]
class Codepromo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_user = null;

    #[ORM\Column]
    private ?int $id_cat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdCat(): ?int
    {
        return $this->id_cat;
    }

    public function setIdCat(int $id_cat): static
    {
        $this->id_cat = $id_cat;

        return $this;
    }
}
