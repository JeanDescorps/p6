<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordReset
{

    /**
     * @Assert\NotBlank(
     *      message = "Ce champ est requis !"
     * )
     * @Assert\Email(
     *      message = "Veuillez entrer un email valide !"
     * )
     * @Assert\Length(
     *      max = 254,
     *      maxMessage = "Votre email ne peut pas contenir plus que {{ limit }} caractères !"
     * )
     */
    private $email;

    /**
     * @Assert\NotBlank(
     *      message = "Ce champ est requis !"
     * )
     * @Assert\Length(
     *      min = 8,
     *      max = 254,
     *      minMessage = "Votre mot de passe doit contenir au moins 8 caractères.",
     *      maxMessage = "Votre mot de passe ne peut pas contenir plus que {{ limit }} caractères !"
     * )
     * @Assert\Regex(
     *     pattern = "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)^",
     *     match = true,
     *     message = "Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial !"
     * )
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(
     *      propertyPath = "newPassword",
     *      message = "Le mot de passe n'est pas identique."
     * )
     */
    private $confirmPassword;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
