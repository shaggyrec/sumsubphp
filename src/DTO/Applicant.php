<?php

namespace Shaggyrec\Sumsubphp\DTO;

class Applicant extends AbstractDTO
{
    public string $id;
    public string $createdAt;
    public string $externalUserId;
    public string $inspectionId;
    public array $fixedInfo;
    public string $email;
    public string $phone;
    public RequiredIdDocs $requiredIdDocs;
    public ReviewResponse $review;
    public string $type;
    public array $metadata;
    public string $sourceKey;
    public string $lang;

    /**
     * @var array|Questionnaire[]
     */
    public array $questionnaires;

    public function __construct(array $sumSubData)
    {
        $this->id = $sumSubData['id'];
        $this->createdAt = $sumSubData['createdAt'];
        $this->externalUserId = $sumSubData['externalUserId'];
        $this->inspectionId = $sumSubData['inspectionId'];
        $this->fixedInfo = $sumSubData['fixedInfo'];
        $this->email = $sumSubData['email'];
        $this->phone = $sumSubData['phone'];
        $this->type = $sumSubData['type'];
        $this->requiredIdDocs = new RequiredIdDocs($sumSubData['requiredIdDocs']);
        $this->review = new ReviewResponse($sumSubData['review']);
        $this->sourceKey = $sumSubData['sourceKey'];
        $this->lang = $sumSubData['lang'];
        $this->metadata = $sumSubData['metadata'];
        $this->questionnaires = $sumSubData['questionnaires']
            ? array_map(
                static function (array $q): Questionnaire {
                    return new Questionnaire($q);
                },
                $sumSubData['questionnaires'],
            )
            : [];
    }
}
