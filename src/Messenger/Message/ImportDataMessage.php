<?php

declare(strict_types=1);

namespace App\Messenger\Message;

use App\Entity\Approval;
use Symfony\Component\Uid\Uuid;

class ImportDataMessage
{
    private readonly Uuid $approvalId;

    public function __construct(Approval $approval)
    {
        $id = $approval->getId();

        if (null === $id) {
            throw new \InvalidArgumentException('No id found on approval object');
        }

        $this->approvalId = $id;
    }

    public function getApprovalId(): Uuid
    {
        return $this->approvalId;
    }
}
