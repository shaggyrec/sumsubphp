<?php

namespace Shaggyrec\Sumsubphp;

final class VerificationSteps
{
    /** @var string Phone verification step */
    public const PHONE_VERIFICATION = 'PHONE_VERIFICATION';
    /** @var string Email verification step */
    public const EMAIL_VERIFICATION = 'EMAIL_VERIFICATION';
    /** @var string Questionnaire */
    public const QUESTIONNAIRE = 'QUESTIONNAIRE';
    /** @var string Applicant data */
    public const APPLICANT_DATA = 'APPLICANT_DATA';
    /** @var string Identity step */
    public const IDENTITY = 'IDENTITY';
    /** @var string Proof of residence */
    public const PROOF_OF_RESIDENCE = 'PROOF_OF_RESIDENCE';
    /** @var string Selfie step */
    public const SELFIE = 'SELFIE';

    public const AVAILABLE_STEPS_TO_RESET = [
        self::PHONE_VERIFICATION,
        self::EMAIL_VERIFICATION,
        self::QUESTIONNAIRE,
        self::APPLICANT_DATA,
        self::IDENTITY,
        self::PROOF_OF_RESIDENCE,
        self::SELFIE,
    ];
}