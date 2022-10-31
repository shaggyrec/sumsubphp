<?php

namespace Shaggyrec\Sumsubphp\DTO;

class ApplicantStatus extends AbstractDTO
{
    public const STATUS_INIT = 'init';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PRECHECKED = 'prechecked';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ON_HOLD = 'onHold';

    public string $createDate;
    public ReviewResult $reviewResult;
    public string $reviewStatus;
}