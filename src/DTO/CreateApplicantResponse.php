<?php

namespace Shaggyrec\Sumsubphp\DTO;

class CreateApplicantResponse extends AbstractDTO
{
    public string $id;
    public string $createdAt;
    public string $clientId;
    public string $inspectionId;
    public string $externalUserId;
    public array $fixedInfo;
    public string $email;
    public string $phone;
    public RequiredIdDocs $requiredIdDocs;
    public ReviewResponse $review;
    public string $type;

    public function __construct(array $sumSubData)
    {
        $this->id = $sumSubData['id'];
        $this->createdAt = $sumSubData['createdAt'];
        $this->clientId = $sumSubData['clientId'];
        $this->inspectionId = $sumSubData['inspectionId'];
        $this->fixedInfo = $sumSubData['fixedInfo'];
        $this->email = $sumSubData['email'];
        $this->phone = $sumSubData['phone'];
        $this->type = $sumSubData['type'];
        $this->requiredIdDocs = new RequiredIdDocs($sumSubData['requiredIdDocs']);
        $this->review = new ReviewResponse($sumSubData['review']);
    }
}