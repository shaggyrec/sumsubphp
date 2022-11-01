<?php

namespace Shaggyrec\Sumsubphp\DTO;

class ReviewResponse extends AbstractDTO
{
    public bool $reprocessing;
    public string $createDate;
    public string $reviewStatus;
    public int $elapsedSincePendingMs;
    public int $elapsedSinceQueuedMs;
    public string $levelName;
    public ReviewResult $reviewResult;
    public string $reviewDate;
}