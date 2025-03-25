<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\TimePeriod;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TimePeriodVoter extends Voter
{
    public const CAN_EXPORT = 'time-period_export';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof TimePeriod) {
            return false;
        }

        if ($attribute !== self::CAN_EXPORT) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var TimePeriod $timePeriod */
        $timePeriod = $subject;


        return match ($attribute) {
            self::CAN_EXPORT => $this->canExport($timePeriod),

            default => throw new \InvalidArgumentException('Attribute not supported'),
        };
    }

    private function canExport(TimePeriod $timePeriod): bool
    {
        return $timePeriod->getPeriodEnd() < new \DateTimeImmutable() && !$timePeriod->isActive();
    }
}
