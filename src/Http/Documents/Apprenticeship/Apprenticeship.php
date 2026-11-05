<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Apprenticeship;

/**
 * Handler for apprenticeship start (E3.4 / WebE34) submissions.
 */
class Apprenticeship extends ApprenticeshipDocument
{
    private const ACTION = 'WebE34';

    protected function action(): string
    {
        return self::ACTION;
    }
}
