<?php
/**
 * Created by PhpStorm.
 * User: Arian-PC
 * Date: 19/05/2019
 * Time: 15:26
 */

namespace App\Exception;


use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountDeletedException extends AccountStatusException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Cuenta eliminada.';
    }
}