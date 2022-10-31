<?php

namespace Shaggyrec\Sumsubphp\DTO\Webhooks;

use Shaggyrec\Sumsubphp\DTO\AbstractDTO;
use Shaggyrec\Sumsubphp\DTO\ReviewResult;

class VerificationResults extends AbstractDTO
{
    public string $applicantId;
    public string $inspectionId;
    public string $correlationId;
    public string $externalUserId;
    public string $levelName;
    public ReviewResult $reviewResult;
    public string $reviewStatus;
    public string $createdAt;
}