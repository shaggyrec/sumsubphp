<?php

namespace Shaggyrec\Sumsubphp\DTO;

class FixedInfo extends AbstractDTO
{
    public string $firstName;
    public string $lastName;
    public string $middleName;
    public string $firstNameEn;
    public string $lastNameEn;
    public string $middleNameEn;
    public string $legalName;
    public string $gender;
    public string $dob;
    public string $placeOfBirth;
    public string $country;
    public string $nationality;
    public array $addresses;
    public array $idDocs;
}