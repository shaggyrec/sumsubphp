<?php

namespace Shaggyrec\Sumsubphp\DTO;

class AccessToken extends AbstractDTO
{
    /**
     * A newly generated access token for an applicant.
     *
     * @var string
     */
    public string $token;

    /**
     * @var string
     */
    public string $userId;
}