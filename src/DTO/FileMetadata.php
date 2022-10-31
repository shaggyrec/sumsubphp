<?php

namespace Shaggyrec\Sumsubphp\DTO;

class FileMetadata extends AbstractDTO
{
    /** @var string An ID card */
    public const ID_CARD = 'ID_CARD';
    /** @var string A passport */
    public const PASSPORT = 'PASSPORT';
    /** @var string  A driving license */
    public const DRIVERS = 'DRIVERS';
    /** @var string Residence permit or registration document in the foreign city/country */
    public const RESIDENCE_PERMIT = 'RESIDENCE_PERMIT';
    /** @var string Proof of address document. Check here for the full list of acceptable docs as UTILITY_BILL */
    public const UTILITY_BILL = 'UTILITY_BILL';
    /** @var string A selfie with a document */
    public const SELFIE = 'SELFIE';
    /** @var string A selfie video (can be used in webSDK or mobileSDK) */
    public const VIDEO_SELFIE = 'VIDEO_SELFIE';
    /** @var string A profile image, i.e. avatar (in this case no additional metadata should be sent) */
    public const PROFILE_IMAGE = 'PROFILE_IMAGE';
    /** @var string Photo from an ID doc (like a photo from a passport) (No additional metadata should be sent) */
    public const ID_DOC_PHOTO = 'ID_DOC_PHOTO';
    /** @var string Agreement of some sort, e.g. for processing personal info */
    public const AGREEMENT = 'AGREEMENT';
    /** @var string Some sort of contract */
    public const CONTRACT = 'CONTRACT';
    /** @var string Translation of the driving license required in the target country */
    public const DRIVERS_TRANSLATION = 'DRIVERS_TRANSLATION';
    /** @var string A document from an investor, e.g. documents which disclose assets of the investor */
    public const INVESTOR_DOC = 'INVESTOR_DOC';
    /** @var string Certificate of vehicle registration */
    public const VEHICLE_REGISTRATION_CERTIFICATE = 'VEHICLE_REGISTRATION_CERTIFICATE';
    /** @var string A proof of income */
    public const INCOME_SOURCE = 'INCOME_SOURCE';
    /** @var string Entity confirming payment (like bank card, crypto wallet, etc) */
    public const PAYMENT_METHOD = 'PAYMENT_METHOD';
    /** @var string A bank card, like Visa or Maestro */
    public const BANK_CARD = 'BANK_CARD';
    /** @var string COVID vaccination document (may contain the QR code) */
    public const COVID_VACCINATION_FORM = 'COVID_VACCINATION_FORM';
    /** @var string  Should be used only when nothing else applies */
    public const OTHER = 'OTHER';

    /**
     * @var string Doc Type
     */
    public string $idDocType;

    /**
     * @var string FRONT_SIDE, BACK_SIDE or null
     */
    public string $idDocSubType;

    /**
     * @var string 3-letter country code (https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3)
     */
    public string $country;

    /**
     * @var string First name
     */
    public string $firstName;

    /**
     * @var string Middle name
     */
    public string $middleName;

    /**
     * @var string Last name
     */
    public string $lastName;

    /**
     * @var string Issued date (format YYYY-mm-dd, e.g. 2001-09-25)
     */
    public string $issuedDate;

    /**
     * @var string Valid until date (format YYYY-mm-dd, e.g. 2001-09-26)
     */
    public string $validUntil;

    /**
     * @var string Document number
     */
    public string $number;

    /**
     * @var string Date of birth
     */
    public string $dob;

    /**
     * @var string Place of birth
     */
    public string $placeOfBirth;
}