<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminVoter extends Voter
{
    public const CAN_DELETE = 'admin_delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof User) {
            return false;
        }

        if (!\in_array($attribute, [self::CAN_DELETE], true)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $admin */
        $admin = $subject;

        /** @var User $user */
        $user = $token->getUser();

        return match ($attribute) {
            self::CAN_DELETE => $this->canDelete($admin, $user),

            default => throw new \InvalidArgumentException('Attribute not supported'),
        };
    }

    /**
     * A user can not delete itself.
     */
    private function canDelete(User $admin, User $user): bool
    {
        return $admin->getId() !== $user->getId();
    }
}
