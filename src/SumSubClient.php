<?php

namespace Shaggyrec\Sumsubphp;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shaggyrec\Sumsubphp\DTO\AddDocResponse;
use Shaggyrec\Sumsubphp\DTO\Applicant;
use Shaggyrec\Sumsubphp\DTO\ApplicantStatus;
use Shaggyrec\Sumsubphp\DTO\FileMetadata;
use Shaggyrec\Sumsubphp\DTO\FixedInfo;
use Shaggyrec\Sumsubphp\DTO\RequiredIdDocs;
use Shaggyrec\Sumsubphp\DTO\RiskLevel;
use Shaggyrec\Sumsubphp\Exception\ClientResponseException;
use Shaggyrec\Sumsubphp\DTO\AccessToken;
use Shaggyrec\Sumsubphp\Exception\IncorrectParamsException;

class SumSubClient
{
    private const BASE_URI = 'https://api.sumsub.com';

    private const HTTP_METHOD_GET = 'GET';
    private const HTTP_METHOD_POST = 'POST';
    private const HTTP_METHOD_PATCH = 'PATCH';
    private const HTTP_METHOD_DELETE = 'DELETE';

    private ClientInterface $client;

    private RequestSigner $requestSigner;

    /**
     * @param string $apiToken
     * @param string $secretKey
     * @return SumSubClient
     */
    public static function getInstance(string $apiToken, string $secretKey): self
    {
        return new self(
            new Client(),
            new RequestSigner($apiToken, $secretKey),
        );
    }

    /**
     * @param ClientInterface $client
     * @param RequestSigner $requestSigner
     */
    public function __construct(ClientInterface $client, RequestSigner $requestSigner)
    {
        $this->client = $client;
        $this->requestSigner = $requestSigner;
    }

    /**
     * @param string $userId
     * @param string $levelName
     * @param int|null $ttlInSecs
     * @throws ClientResponseException|ClientExceptionInterface
     * @return AccessToken
     */
    public function getAccessToken(string $userId, string $levelName, ?int $ttlInSecs = null): AccessToken
    {
        return new AccessToken(
            $this->sendRequest(
                sprintf(
                    '/resources/accessTokens?%s',
                    http_build_query(
                        array_merge(
                            [
                                'userId' => $userId,
                                'levelName' => $levelName,
                            ],
                            $ttlInSecs ? ['ttlInSecs' => $ttlInSecs] : [],
                        )
                    ),
                ),
                self::HTTP_METHOD_POST,
            )
        );
    }

    /**
     * @param string $externalUserId
     * @param string $phone
     * @param string $email
     * @param FixedInfo|null $fixedInfo
     * @param string|null $levelName
     * @param string|null $sourceKey
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return Applicant
     */
    public function createApplicant(
        string $externalUserId,
        string $phone,
        string $email,
        ?FixedInfo $fixedInfo = null,
        ?string $levelName = null,
        ?string $sourceKey = null
    ): Applicant {
        return new Applicant(
            $this->sendRequest(
                '/resources/applicants',
                self::HTTP_METHOD_POST,
                array_merge(
                    [
                        'externalUserId' => $externalUserId,
                        'email' => $email,
                        'phone' => $phone,
                    ],
                    $fixedInfo !== null ? $fixedInfo->toArray() : [],
                    $levelName ? ['ttlInSecs' => $levelName] : [],
                    $sourceKey ? ['sourceKey' => $sourceKey] : [],
                ),
            ),
        );
    }

    /**
     * @param string $applicantId
     * @param string $levelName
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return Applicant
     */
    public function changeLevel(string $applicantId, string $levelName): Applicant
    {
        return new Applicant(
            $this->sendRequest(
                sprintf('/resources/applicants/%s/moveToLevel?name=%s', $applicantId, $levelName),
                self::HTTP_METHOD_POST,
                [],
            )
        );
    }

    /**
     * @param string $applicantId
     * @param FileMetadata $metadata
     * @param resource $content
     * @throws ClientExceptionInterface|ClientResponseException
     * @return AddDocResponse
     */
    public function addDoc(string $applicantId, FileMetadata $metadata, $content): AddDocResponse
    {
        return new AddDocResponse(
            $this->decodeRequest(
                $this->client->sendRequest(
                    $this->requestSigner->sign(
                        new Request(
                            self::HTTP_METHOD_POST,
                            $this->buildUrl(
                                sprintf(
                                    '/resources/applicants/%s/info/idDoc',
                                    $applicantId,
                                ),
                                []
                            ),
                            [],
                            new MultipartStream([
                                'metadata' => $metadata,
                                'contents' => $content,
                            ]),
                        )
                    ),
                )
            )
        );
    }

    /**
     * @param string $applicantId
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return Applicant
     */
    public function getApplicantById(string $applicantId): Applicant
    {
        return new Applicant(
            $this->sendRequest(
                sprintf(
                    '/resources/applicants/%s/one',
                    $applicantId,
                ),
            )
        );
    }

    /**
     * @param string $externalUserId
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return Applicant
     */
    public function getApplicantByExternalUserId(string $externalUserId): Applicant
    {
        return new Applicant(
            $this->sendRequest(
                sprintf(
                    '/resources/applicants/-;externalUserId=%s/one',
                    $externalUserId,
                ),
            )
        );
    }

    /**
     * @param string $applicantId
     * @param FixedInfo $info
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return void
     */
    public function changeProvidedInfo(string $applicantId, FixedInfo $info): void
    {
        $this->sendRequest(
            sprintf('/resources/applicants/%s', $applicantId),
            self::HTTP_METHOD_PATCH,
            $info->toArray(),
        );
    }

    /**
     * @param string $applicantId
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return ApplicantStatus
     */
    public function getApplicantStatus(string $applicantId): ApplicantStatus
    {
        return new ApplicantStatus(
            $this->sendRequest(
                sprintf('/resources/applicants/%s/status', $applicantId),
            )
        );
    }

    /**
     * @param string $applicantId
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return RequiredIdDocs
     */
    public function getRequiredIdDocsStatus(string $applicantId): RequiredIdDocs
    {
        return new RequiredIdDocs(
            $this->sendRequest(
                sprintf(
                    '/resources/applicants/%s/requiredIdDocsStatus',
                    $applicantId,
                ),
            )
        );
    }

    /**
     * @param string $applicantId
     * @param string|null $reason
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return void
     */
    public function requestCheck(string $applicantId, ?string $reason = null): void
    {
        $this->sendRequest(
            sprintf(
                '/resources/applicants/%s/status/pending%s',
                $applicantId,
                $reason !== null
                    ? '?reason=' . $reason
                    : ''
            ),
        );
    }

    /**
     * @param string $inspectionId
     * @param string $imageId
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return mixed
     */
    public function getDocumentImage(string $inspectionId, string $imageId)
    {
        return $this->sendRequest(
            sprintf(
                '/resources/inspections/%s/resources/%s',
                $inspectionId,
                $imageId,
            ),
        );
    }

    /**
     * @param string $applicantId
     * @param string $note
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return Applicant
     */
    public function addToBlacklist(string $applicantId, string $note = ''): Applicant
    {
        return new Applicant(
            $this->sendRequest(
                sprintf(
                    '/resources/applicants/%s/blacklist?note=%s',
                    $applicantId,
                    $note,
                ),
                self::HTTP_METHOD_POST,
            )
        );
    }

    /**
     * @param string $applicantId
     * @param string $step
     * @throws ClientExceptionInterface
     * @throws ClientResponseException|IncorrectParamsException
     * @return void
     */
    public function resetSingleVerificationStep(string $applicantId, string $step): void
    {
        if (!in_array($step, VerificationSteps::AVAILABLE_STEPS_TO_RESET)) {
            throw new IncorrectParamsException(
                sprintf(
                    'Param step mast be one of [%s]',
                    implode(', ', VerificationSteps::AVAILABLE_STEPS_TO_RESET),
                )
            );
        }
        $this->sendRequest(
            sprintf(
                '/resources/applicants/%s/resetStep/%s',
                $applicantId,
                $step,
            ),
            self::HTTP_METHOD_POST,
        );
    }

    /**
     * @param string $applicantId
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return void
     */
    public function resetApplicant(string $applicantId): void
    {
        $this->sendRequest(
            sprintf(
                '/resources/applicants/%s/reset',
                $applicantId,
            ),
            self::HTTP_METHOD_POST,
        );
    }

    /**
     * @param Applicant $applicant
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return Applicant
     */
    public function changeTopLevelInfo(Applicant $applicant): Applicant
    {
        return new Applicant(
            $this->sendRequest(
                '/resources/applicants/',
                self::HTTP_METHOD_POST,
                $applicant->toArray(),
            )
        );
    }

    /**
     * @param string $applicantId
     * @param string $riskLevel
     * @param string $comment
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @throws IncorrectParamsException
     * @return RiskLevel
     */
    public function setRiskLevel(string $applicantId, string $riskLevel, string $comment): RiskLevel
    {
        if (!in_array($riskLevel, RiskLevel::AVAILABLE_RISK_LEVELS)) {
            throw new IncorrectParamsException(
                sprintf(
                    'Param $riskLevel mast be one of [%s]',
                    implode(', ', VerificationSteps::AVAILABLE_STEPS_TO_RESET),
                )
            );
        }

        return new RiskLevel(
            $this->sendRequest(
                sprintf(
                    '/resources/applicants/%s/riskLevel/entries',
                    $applicantId
                ),
                self::HTTP_METHOD_POST,
                [
                    'comment' => $comment,
                    'riskLevel' => $riskLevel,
                ],
            )
        );
    }

    /**
     * @param string $applicantId
     * @param array $tags
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @throws IncorrectParamsException
     * @return void
     */
    public function addTags(string $applicantId, array $tags): void
    {
        if ($tags === []) {
            throw new IncorrectParamsException(
                'At least one tag must be specified',
            );
        }

        $this->sendRequest(
            sprintf('/resources/applicants/%s/tags', $applicantId),
            self::HTTP_METHOD_POST,
            $tags,
        );
    }

    /**
     * @param string $inspectionId
     * @param string $imageId
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return void
     */
    public function markImageDeleted(string $inspectionId, string $imageId): void
    {
        $this->sendRequest(
            sprintf(
                '/resources/inspections/%s/resources/%s',
                $inspectionId,
                $imageId
            ),
            self::HTTP_METHOD_DELETE,
        );
    }

    /**
     * @param string $secretKey
     * @param $payload
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return string
     */
    public function testDigest(string $secretKey, $payload): string
    {
        return json_decode(
            $this->client->sendRequest(
                $this->requestSigner->sign(
                    new Request(
                        self::HTTP_METHOD_POST,
                        $this->buildUrl(
                            '/resources/inspectionCallbacks/testDigest',
                            ['secretKey' => $secretKey],
                        ),
                        [],
                        $payload,
                    )
                ),
            )->getBody()->getContents(),
        )->digest;
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $params
     * @throws ClientExceptionInterface
     * @throws ClientResponseException
     * @return mixed
     */
    private function sendRequest(
        string $path,
        string $method = self::HTTP_METHOD_GET,
        array $params = []
    ) {
        return $this->decodeRequest($this->client->sendRequest($this->buildRequest($path, $method, $params)));
    }

    /**
     * @param ResponseInterface $r
     * @throws ClientResponseException
     * @return mixed
     */
    private function decodeRequest(ResponseInterface $r): mixed
    {
        $result = $r->getBody()->getContents();
        if (in_array('application/json', $r->getHeader('Content-Type'))) {
            $result = json_decode($result, true);
            if ($result === null) {
                throw new ClientResponseException(json_last_error_msg());
            }
        }

        if ($r->getStatusCode() > 399) {
            throw new ClientResponseException($result['description']);
        }

        return $result;
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $params
     * @return RequestInterface
     */
    private function buildRequest(
        string $path,
        string $method = self::HTTP_METHOD_GET,
        array $params = []
    ): RequestInterface {
        return $this->requestSigner->sign(
            new Request(
                $method,
                $this->buildUrl(
                    $path,
                    $method === self::HTTP_METHOD_GET
                        ? $params
                        : []
                ),
                [],
                $params !== [] && $method !== self::HTTP_METHOD_GET ? json_encode($params) : null,
            ),
        );
    }

    /**
     * @param string $path
     * @param array $queryParams
     * @return string
     */
    private function buildUrl(string $path, array $queryParams): string
    {
        return sprintf(
            '%s%s%s',
            self::BASE_URI,
            $path,
            $queryParams !== [] ? '?' . http_build_query($queryParams) : '',
        );
    }
}