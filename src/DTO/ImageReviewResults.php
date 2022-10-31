<?php

namespace Shaggyrec\Sumsubphp\DTO;

class ImageReviewResults extends AbstractDTO
{
    public int $id;

    public ReviewResult $reviewResult;

    public function __construct(int $id, array $reviewResult)
    {
        $this->id = $id;
        $this->reviewResult = new ReviewResult($reviewResult);
    }
}