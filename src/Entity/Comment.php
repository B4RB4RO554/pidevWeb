<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank()
     */
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Event $comments = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text=null): self
    {
        $this->text = $text;

        return $this;
    }

    public function getComments(): ?Event
    {
        return $this->comments;
    }

    public function setComments(?Event $comments): self
    {
        $this->comments = $comments;

        return $this;
    }
}
