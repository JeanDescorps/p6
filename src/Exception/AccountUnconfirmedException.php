<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountUnconfirmedException extends AccountStatusException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Vous devez activer votre compte pour vous connecter.';
    }
}
