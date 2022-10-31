<?php

namespace Shaggyrec\Sumsubphp;

use Psr\Http\Message\RequestInterface;

final class RequestSigner
{
    /**
     * @var string
     */
    private string $appToken;

    /**
     * @var string
     */
    private string $secretKey;

    /**
     * @var int|null
     */
    private ?int $time = null;

    public function __construct(string $appToken, string $secretKey)
    {
        $this->appToken = $appToken;
        $this->secretKey = $secretKey;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;
        return $this;
    }

    public function sign(RequestInterface $request): RequestInterface
    {
        $currentTimestamp = $this->time ?? time();

        $httpMethod = strtoupper($request->getMethod());
        $url = $request->getUri()->getPath();
        $query = $request->getUri()->getQuery();
        if ($query !== '') {
            $url .= '?' . $query;
        }

        $signature = hash_hmac(
            'sha256',
            $currentTimestamp . $httpMethod . $url . $request->getBody()->getContents(),
            $this->secretKey
        );

        return $request
            ->withHeader('X-App-Token', $this->appToken)
            ->withHeader('X-App-Access-Ts', $currentTimestamp)
            ->withHeader('X-App-Access-Sig', $signature);
    }
}