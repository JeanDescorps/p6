<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    /**
     * @Assert\Image()
     */
    private $image;

    private $path;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(UploadedFile $image = null)
    {
        $this->image = $image;
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
    public function handleImage()
    {
        if ($this->image === null) {
            return;
        }

        if ($this->id) {
            unlink($this->path . '/' . $this->name);
        }

        // Image name creation
        $name = $this->createName();
        // Setting name
        $this->setName($name);
        // Moving image into the image repository
        $this->image->move($this->path, $name);

    }

    private function createName(): string
    {
        return md5(uniqid()) . '.' . $this->image->guessClientExtension();
    }
}
