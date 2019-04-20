<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *      fields={"title"},
 *      message="Ce trick existe déjà."
 * )
 */
class Trick
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message = "Ce champ est requis !"
     * )
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "Votre titre de trick doit contenir au moins {{ limit }} caractères !",
     *      maxMessage = "Votre titre de trick ne peut pas contenir plus que {{ limit }} caractères !"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *      message = "Ce champ est requis !"
     * )
     * @Assert\Length(
     *      max = 10000,
     *      maxMessage = "La description du trick ne peut pas contenir plus que {{ limit }} caractères !"
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="trick", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="trick", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    private $videos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="trick", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank(
     *      message = "Ce champ est requis !"
     * )
     * @Assert\Length(
     *      max = 300,
     *      maxMessage = "Le chapô du trick ne peut pas contenir plus que {{ limit }} caractères !"
     * )
     */
    private $lead;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Assert\Image(
     *      maxSize = "1M",
     *      maxSizeMessage = "Votre avatar ne doit pas dépasser 1 Mo",
     * )
     */
    private $file;

    private $path;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setTrick($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getTrick() === $this) {
                $image->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    public function getLead(): ?string
    {
        return $this->lead;
    }

    public function setLead(string $lead): self
    {
        $this->lead = $lead;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @ORM\PreFlush()
     */
    public function handleFile()
    {
        if ($this->file === null) {
            return;
        }

        // Delete image from the server if update
        if ($this->id && $this->image !== 'default-trick.jpg') {
            unlink($this->path . '/' . $this->image);
        }

        // Image name creation
        $name = $this->createName();
        // Setting name
        $this->setImage($name);
        // Moving image into the image repository
        $this->file->move($this->path, $name);

    }

    private function createName(): string
    {
        return md5(uniqid()) . '.' . $this->file->guessClientExtension();
    }

}
