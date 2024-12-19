<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank()
     */
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    /**
     *  @ORM\Column(type="datetime")
     * @Assert\GreaterThanOrEqual("today", message="La date de début de l'événement doit être après aujourd'hui.")
     */
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThanOrEqual(propertyPath="datedebut", message="La date de fin doit être après la date de début")
     */
    private ?\DateTimeInterface $datefin = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank()
     */
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'comments', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;



    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description=null): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title=null): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setComments($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getComments() === $this) {
                $comment->setComments(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return(string) $this->id;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }




}
