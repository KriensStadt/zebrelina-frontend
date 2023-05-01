<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Admin;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminVoter extends Voter
{
    public const CAN_DELETE = 'admin_delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof Admin) {
            return false;
        }

        if (!\in_array($attribute, [self::CAN_DELETE], true)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var Admin $admin */
        $admin = $subject;

        /** @var Admin $user */
        $user = $token->getUser();

        return match ($attribute) {
            self::CAN_DELETE => $this->canDelete($admin, $user),

            default => throw new \InvalidArgumentException('Attribute not supported'),
        };
    }

    /**
     * A user can not delete itself.
     */
    private function canDelete(Admin $admin, Admin $user): bool
    {
        return $admin->getId() !== $user->getId();
    }
}
