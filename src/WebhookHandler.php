<?php

namespace Shaggyrec\Sumsubphp;

use Psr\Http\Message\RequestInterface;
use Shaggyrec\Sumsubphp\DTO\Webhooks\VerificationResults;
use Shaggyrec\Sumsubphp\DTO\Webhooks\WebhookResponse;
use Shaggyrec\Sumsubphp\Exception\SenderNowVerifiedException;

final class WebhookHandler
{
    /**
     * @param array|string $response
     * @return VerificationResults
     */
    public static function verificationResults(array|string $response): VerificationResults
    {
        return new VerificationResults(self::decodeResponse($response));
    }

    /**
     * @param array|string $response
     * @return WebhookResponse
     */
    public static function handle(array|string $response): WebhookResponse
    {
        return new WebhookResponse(self::decodeResponse($response));
    }

    /**
     * @param string $digest
     * @param $payload
     * @param string $secretKey
     * @throws SenderNowVerifiedException
     * @return void
     */
    public static function verifySender(string $digest, $payload, string $secretKey): void
    {
        if (hash_hmac('sha1', $payload, $secretKey) !== $digest) {
            throw new SenderNowVerifiedException('Digest is wrong');
        }
    }

    /**
     * @param RequestInterface $r
     * @param string $secretKey
     * @throws SenderNowVerifiedException
     * @return WebhookResponse
     */
    public static function handleWithVerification(RequestInterface $r, string $secretKey): WebhookResponse
    {
        $content = $r->getBody()->getContents();
        self::verifySender($r->getHeader('x-payload-digest')[0], $content, $secretKey);

        return self::handle($content);
    }

    /**
     * @param array|string $response
     * @return array
     */
    private static function decodeResponse(array|string $response): array
    {
        return is_string($response)
            ? json_decode($response, true)
            : $response;
    }
}