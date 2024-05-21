<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Post;
use App\Entity\User;

class PostsVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const DELETE = 'POST_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Post;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case self::DELETE:
                return $this->canDelete($subject, $user);
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }

    private function canEdit(Post $post, User $user): bool
    {
        return $user === $post->getUsers();
    }

    private function canDelete(Post $post, User $user): bool
    {
        return $user === $post->getUsers();
    }
}
