<?php

namespace Shaggyrec\Sumsubphp\DTO;

class ReviewResult extends AbstractDTO
{
    public const REVIEW_ANSWER_RED = 'RED';
    public const REVIEW_ANSWER_GREEN = 'GREEN';

    public const REVIEW_REJECT_TYPE_FINAL = 'FINAL';
    public const REVIEW_REJECT_TYPE_RETRY = 'RETRY';

    public string $moderationComment;
    // A human-readable comment that should not be shown to an end user, and is meant to be read by a client
    // This field will contain applicant's top-level comments,
    // plus, if the rejectType is not RETRY it may contain some private info, like that the user is a fraudster.
    // we envision that this field will be used for admin areas of our clients,
    // where a human can get all information
    public string $clientComment;
    // final answer that should be highly trusted (only 'RED' and 'GREEN' are currently supported)
    public string $reviewAnswer;
    // a machine-readable constant that describes the problems in case of verification failure
    public array $rejectLabels;
    public string $reviewRejectType;
}