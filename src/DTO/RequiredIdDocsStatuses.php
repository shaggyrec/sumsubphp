<?php

namespace Shaggyrec\Sumsubphp\DTO;

class RequiredIdDocsStatuses extends AbstractDTO
{
    /**
     * @var RequiredIdDocsStatus[]
     */
    public array $statuses = [];

    public function __construct(array $sumSubData)
    {
        foreach ($sumSubData as $step => $data) {
            $this->statuses[] = new RequiredIdDocsStatus($step, $data);
        }
    }
}