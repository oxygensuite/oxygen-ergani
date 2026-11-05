<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Apprenticeship;

/**
 * Handler for apprenticeship start correction (E3.4 / WebE34C) submissions.
 *
 * Corrects a previous E3.4 declaration, referenced through
 * `setRelatedProtocol()` and `setRelatedDate()` on the declaration.
 */
class ApprenticeshipCorrection extends ApprenticeshipDocument
{
    private const ACTION = 'WebE34C';

    protected function action(): string
    {
        return self::ACTION;
    }
}
