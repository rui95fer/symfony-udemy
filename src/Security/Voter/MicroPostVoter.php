<?php

namespace App\Security\Voter;

use App\Entity\MicroPost;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

final class MicroPostVoter extends Voter
{
    public const CREATE = 'MICROPOST_CREATE';
    public const EDIT = 'MICROPOST_EDIT';
    public const DELETE = 'MICROPOST_DELETE';

    public function __construct(
        private AccessDecisionManagerInterface $accessDecisionManager,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        if (!in_array($attribute, [self::CREATE, self::EDIT, self::DELETE])) {
            return false;
        }

        if ($attribute === self::CREATE) {
            return true;
        }

        if (!$subject instanceof MicroPost) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Authenticated users can create micro posts
        if ($attribute === self::CREATE) {
            return true;
        }

        $microPost = $subject;

        // ... (check conditions and return true to grant permission) ...
        if ($this->accessDecisionManager->decide($token, ['ROLE_ADMIN'])) {
            return true;
        }

        if ($microPost->getUser() === $user) {
            return true;
        }

        return false;
    }
}
