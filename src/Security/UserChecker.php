<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\AccountUnconfirmedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserChecker checks the user account flags.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function checkPreAuth(UserInterface $user): void
    {}

    /**
     * Checking if user is confirmed
     *
     * @param UserInterface $user
     *
     * @return void
     *
     * @throws AccountUnconfirmedException
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->getConfirmed()) {
            throw new AccountUnconfirmedException('Vous devez valider votre compte avant de pouvoir vous connecter.');
        }
    }
}
