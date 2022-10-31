<?php

namespace Shaggyrec\Sumsubphp\DTO;

class RequiredIdDocsStatus extends AbstractDTO
{
    public string $step;
    public ReviewResult $reviewResult;
    public string $country;
    public string $idDocType;
    public array $imageIds;
    /**
     * @var ImageReviewResults[]
     */
    public array $imageReviewResults = [];

    public function __construct(string $step, array $statusData)
    {
        $this->step = $step;
        $this->reviewResult = new ReviewResult($statusData['reviewResult']);
        $this->country = $statusData['country'];
        $this->idDocType = $statusData['idDocType'];
        $this->imageIds = $statusData['imageIds'];
        $this->imageReviewResults = array_map(
            static function (string $id, array $review) {
                return new ImageReviewResults($id, $review);
            },
            array_keys($statusData['imageReviewResults']),
            $statusData['imageReviewResults'],
        );
    }
}