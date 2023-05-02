<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Approval;

class ApprovalChangeEvent
{
    public function __construct(
        private readonly Approval $approval,
    ) {
    }

    public function getApproval(): Approval
    {
        return $this->approval;
    }
}
