<?php

namespace Shaggyrec\Sumsubphp\DTO;

class RiskLevel extends AbstractDTO
{
    public const UNKNOWN = 'unknown';
    public const LOW = 'low';
    public const MEDIUM = 'medium';
    public const HIGH = 'high';

    public const AVAILABLE_RISK_LEVELS = [
        self::UNKNOWN,
        self::LOW,
        self::MEDIUM,
        self::HIGH,
    ];

    public string $riskLevel;
    public array $entries;
}