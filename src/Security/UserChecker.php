<?php

namespace App\Security;

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
    public function checkPreAuth(UserInterface $user)
    {

    }

    /**
     * Checking if user is confirmed
     *
     * @param UserInterface $user
     *
     * @return Exception
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user->getConfirmed()) {
            throw new AccountUnconfirmedException('Vous devez valider votre compte avant de pouvoir vous connecter.');
        }
    }
}
