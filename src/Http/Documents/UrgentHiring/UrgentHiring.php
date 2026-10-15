<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\UrgentHiring;

/**
 * Handler for urgent hiring declaration (FastAna) submissions.
 *
 * Used for announcing a hiring to cover urgent needs.
 */
class UrgentHiring extends UrgentHiringDocument
{
    private const ACTION = 'FastAna';

    protected function action(): string
    {
        return self::ACTION;
    }
}
