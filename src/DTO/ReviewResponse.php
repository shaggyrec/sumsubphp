<?php

namespace Shaggyrec\Sumsubphp\DTO;

class ReviewResponse extends AbstractDTO
{
    public bool $reprocessing;
    public string $createDate;
    public string $reviewStatus;
}