<?php

namespace OxygenSuite\OxygenErgani\Enums;

/**
 * ERGANI API environment.
 */
enum Environment
{
    case PRODUCTION;

    case TEST;

    public function getApiUrl(): string
    {
        return match ($this) {
            self::PRODUCTION => 'https://eservices.yeka.gr/WebservicesAPI/api/',
            self::TEST => 'https://trialv2eservices.yeka.gr/WebservicesAPI/Api/',
        };
    }
}
