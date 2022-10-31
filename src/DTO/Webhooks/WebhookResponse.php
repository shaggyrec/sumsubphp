<?php

namespace Shaggyrec\Sumsubphp\DTO\Webhooks;

use Shaggyrec\Sumsubphp\DTO\AbstractDTO;
use Shaggyrec\Sumsubphp\DTO\ReviewResult;

class WebhookResponse extends AbstractDTO
{
    public string $applicantId;

    public string $inspectionId;

    public string $correlationId;

    public string $levelName;

    public string $externalUserId;

    public string $type;

    public string $sandboxMode;

    public string $reviewStatus;

    public string $videoIdentReviewStatus;

    public string $createdAt;

    public string $clientId;

    public ?ReviewResult $reviewResult = null;
}