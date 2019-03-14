<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordForgot
{
    /**
     * @Assert\NotBlank(
     *      message = "Ce champ est requis !"
     * )
     */
    private $username;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
