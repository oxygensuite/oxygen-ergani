<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\UrgentHiring;

/**
 * Handler for urgent hiring correction (FastAnaC) submissions.
 *
 * Corrects or changes a previous FastAna declaration, referenced through
 * `setRelatedProtocol()` and `setRelatedDate()` on the declaration.
 */
class UrgentHiringCorrection extends UrgentHiringDocument
{
    private const ACTION = 'FastAnaC';

    protected function action(): string
    {
        return self::ACTION;
    }
}
