# SumSub PHP client

PHP API client for sumsub.com

## Installation

```
composer require shaggyrec/sumsubphp
``` 

## Usage

```php
use Shaggyrec\SumSubClient\SumSubClient;

$sumSub = SumSubClient::getInstance('App Token', 'APP Secret');

$sumSub->getAccessToken('userId', 'levelName');
```

## Methods

### Getting access token
```php
$sumSub->getAccessToken('userId', 'levelName');
```

#### Create an Applicant

An applicant is an entity representing one physical person. It may have several ID documents attached, like an ID card or a passport. Many additional photos of different documents can be attached to the same applicant.

```php
$sumSub->createApplicant(
    'externalUserId',
    'phone',
    'email',
    new FixedInfo(
        [
            firstName => 'firstName', 
            lastName => '',
            middleName => '',
            firstNameEn => '',
            lastNameEn => '',
            middleNameEn => '',
            legalName => '',
            gender => 'M',
            dob => '',
            placeOfBirth => '',
            country => '',
            nationality => '',
            addresses => [],
        ],
    )
    'levelName',
);
```

### Changing required document set (level) 

This method updates required documents list according to the level provided. In case you need to add one more step to the check, for example, only id document and selfie have been requested at first, and after the check has been completed you need to get the proof of address. So you have to add one more step to the current list of required documents.

```php
$sumSub->changeLevel('applicantId', 'levelName');
```


### Adding an ID document
A method gets a multipart form: ID doc JSON metadata and, optionally, a document photo. If the ID doc with this type already exists, its data will be merged. Existing data will be overwritten if they also present in the new object. However, a new image will be added nonetheless. If document metadata are not known yet, just send type and a country. E.g. "PASSPORT" and "GBR". These two fields are mandatory.

```php
use Shaggyrec\Sumsubphp\DTO\FileMetadata;

$sumSub->addDoc(
    '$applicantId',
    new FileMetadata(
        [
            'idDocType' => 'PASSPORT', 
            'country' => 'USA',
            'firstName' => '',
            'middleName' => '',
            'lastName' => '',
            'issuedDate' => '2015-01-02',
            'number' => '40111234567',
            'dob' => '2000-02-01',
            'placeOfBirth' => 'London'
    ],
    $content, // A binary photo of a document
);
```

### Getting applicant data

During the verification we also extract data from the applicant's id docs. To get the full structured view of an applicant you should perform the following request.

```php
$sumSub->getApplicantById('applicantId');
```

Or by your user id

```php
$sumSub->getApplicantByExternalUserId('yourId');
```

### Changing provided info (fixedInfo)

If you'd like to alter data that you've provided us to cross-validate it with documents you can issue a PATCH request instead of creating a new applicant, which is highly discouraged. This method patches the fields in the fixedInfo key of the applicant.

```php

use Shaggyrec\Sumsubphp\DTO\FixedInfo;

$sumSub->changeProvidedInfo(
    'applicantId',
    new FixedInfo(
        [
            firstName => 'firstName', 
            lastName => '',
            middleName => '',
            firstNameEn => '',
            lastNameEn => '',
            middleNameEn => '',
            legalName => '',
            gender => 'M',
            dob => '',
            placeOfBirth => '',
            country => '',
            nationality => '',
            addresses => [],
        ],
    ),
);
```

[Official docs for this.](https://developers.sumsub.com/api-reference/#changing-provided-info-fixedinfo)

### Getting applicant status

It is recommended that you use this method if you are using WebSDK or MobileSDK since SDKs will show rejection reasons and comments within their screens. But if you still need to fetch rejection comments, it's possible using method below.

```php
$sumSub->getApplicantStatus('applicantId'); 
```

### Getting applicant documents status

It is recommended that you use this method if you information about documents

```php
$sumSub->getRequiredIdDocsStatus('applicantId');
```

### Requesting an applicant check

You can programmatically ask us to re-check an applicant in cases where you or your user believe that our system made a mistake, or if you're sending us documents via API and would like for us to perform a check. To do it you should explicitly move an applicant to the pending state by performing the following request.

```php
$sumSub->requestCheck(
    'applicantId',
    'reason', // optional
);
````

### Getting document images

If you are interested in receiving images that were part of the final verification, you should use this method.

```php
$sumSub->getDocumentImage(
    'inspectionId', // Inspection ID (it's a part of an applicant
    'imageId'
);
```
About imageId [https://developers.sumsub.com/api-reference/#getting-applicant-status-api](https://developers.sumsub.com/api-reference/#getting-applicant-status-api)

### Adding an applicant to blocklist

If for some reason you need to add an applicant to the blocklist, you can use this endpoint. It is necessary to add the reason for adding the applicant to the blocklist.

```php
$sumSub->addToBlacklist('applicantId', 'note')
```

### Resetting a single verification step
For some cases it's required for user to pass already passed verification step - method below will allow making step inactive for SDK to run it again and collect new data.

```php
$sumSub->resetSingleVerificationStep('applicantId', 'step');

```

AVAILABLE STEPS TO RESET
```
PHONE_VERIFICATION
EMAIL_VERIFICATION
QUESTIONNAIRE
APPLICANT_DATA
IDENTITY
PROOF_OF_RESIDENCE
SELFIE
```

### Resetting an applicant

In very rare cases, it is required to change the status of the applicant to init. For example, if a user has contacted support with a request to re-pass verification from scratch with new documents.

```php
$sumSub->resetApplicant('applicantId'); 
```


### Set risk level for the applicant

This method allows you to set a risk level for your applicant by your own criteria.

```php 
$sumSub->setRiskLevel(
    'applicantId',
    'riskLevel', // unknown|low|medium|high
    '$comment', // Any string
);
```

### Marking image as inactive (deleted)
That method allows you to mark uploaded image as deleted so during initialization SDK screen would ask for a new one. It can be used in cases you'd like for your users to re-upload document that was previously approved via SDK.

```php 
$sumSub->markImageDeleted('inspectionId', 'imageId');
```

About imageId [https://developers.sumsub.com/api-reference/#getting-applicant-status-api](https://developers.sumsub.com/api-reference/#getting-applicant-status-api)

### Adding custom applicant tags

Use that method to assign custom tags to applicant profiles. Create new tags in the Global settings section of the dashboard

```php 
$sumSub->addTags('applicantId', ['tag1', 'tagg2]);
```

## Handling webhooks

```php
use Shaggyrec\SumSubClient\WebhookHandler;

...
// to verify sender
WebhookHandler::verifySender('x-payload-digest header', $webhookBody, 'secret key')

// to get WebhookResponse object
$webhookResponse = WebhookHandler::handle($webhookBody);
```
Or you could provide Psr\Http\Message\RequestInterface
to WebhookHandler::handleWithVerification function

```php
use Shaggyrec\SumSubClient\WebhookHandler;

... 

/** @var Psr\Http\Message\RequestInterface $webhookBody */
$webhookBody;
$webhookResponse = WebhookHandler::handleWithVerification($webhookBody, 'SECRET KEY');
``` 

## To run integration tests localy you must set up your secret key and api token
In `tests/integration/phpunit.xml.dist`

## Run tests

```
./tests/run.sh
```