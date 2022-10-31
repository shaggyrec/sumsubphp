<?php

namespace unit;


use GuzzleHttp\Psr7\Request;
use Shaggyrec\Sumsubphp\DTO\ReviewResult;
use Shaggyrec\Sumsubphp\Exception\SenderNowVerifiedException;
use Shaggyrec\Sumsubphp\WebhookHandler;
use PHPUnit\Framework\TestCase;

class WebhookHandlerTest extends TestCase
{
    public function testParsesString()
    {
        $r = WebhookHandler::verificationResults(self::hookBody());
        $this->assertSame('5cb744200a975a67ed1798a4', $r->applicantId);
        $this->assertSame(ReviewResult::REVIEW_ANSWER_RED, $r->reviewResult->reviewAnswer);
    }

    public function testParsesArray()
    {
        $r = WebhookHandler::verificationResults(json_decode(self::hookBody(), true));
        $this->assertSame('5cb744200a975a67ed1798a4', $r->applicantId);
        $this->assertSame(ReviewResult::REVIEW_ANSWER_RED, $r->reviewResult->reviewAnswer);
    }

    public function testVerifyDigest()
    {
        $this->assertEmpty(
            WebhookHandler::verifySender(
                '9598d33f25adcb236ea2d82ee50d9c010fa19d7a',
                'payload',
                'ddd',
            )
        );
    }

    public function testThrowsErrorWhenBadDigestWasProvided()
    {
        $this->expectException(SenderNowVerifiedException::class);
        WebhookHandler::verifySender(
            'bad-digest',
            'payload',
            'ddd',
        );
    }

    private static function hookBody(): string
    {
        return <<<JSON
            {
              "applicantId": "5cb744200a975a67ed1798a4",
              "inspectionId": "5cb744200a975a67ed1798a5",
              "correlationId": "req-fa94263f-0b23-42d7-9393-ab10b28ef42d",
              "externalUserId": "externalUserId",
              "levelName": "basic-kyc-level",
              "type": "applicantReviewed",
              "reviewResult": {
                "moderationComment": "We could not verify your profile. Please contact support: support@sumsub.com",
                "clientComment": " Suspected fraudulent account.",
                "reviewAnswer": "RED",
                "rejectLabels": ["UNSATISFACTORY_PHOTOS", "GRAPHIC_EDITOR", "FORGERY"],
                "reviewRejectType": "FINAL"
              },
              "reviewStatus": "completed",
              "createdAt": "2020-02-21 13:23:19+0000"
            }
JSON;
    }
}
