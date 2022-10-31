<?php

namespace Shaggyrec\Sumsubphp\DTO;

class DocSetResponse extends AbstractDTO
{
    public string $idDocSetType;

    public array $types;

    public ?string $videoRequired = null;
}