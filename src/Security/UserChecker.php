<?php
/**
 * Created by PhpStorm.
 * User: Arian-PC
 * Date: 19/05/2019
 * Time: 13:59
 */

namespace App\Security;

use App\Exception\AccountDeletedException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user is deleted, show a generic Account Not Found message.
        if (!$user->isEnabled()) {
            throw new AccountDeletedException('El usuario ha sido eliminado');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
        if (!$user->isAccountNonExpired()) {
            throw new AccountExpiredException('Su cuenta ha expirado');
        }
    }
}