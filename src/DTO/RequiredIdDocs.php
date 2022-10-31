<?php

namespace Shaggyrec\Sumsubphp\DTO;

class RequiredIdDocs extends AbstractDTO
{
    public array $excludedCountries;

    /**
     * @var DocSetResponse[]
     */
    public array $docSets;

    public function __construct(array $sumSubData)
    {
        $this->excludedCountries = $sumSubData['excludedCountries'];
        $this->docSets = array_map(
            static function ($docSet) {
                return new DocSetResponse($docSet);
            },
            $sumSubData['docSets'],
        );
    }
}