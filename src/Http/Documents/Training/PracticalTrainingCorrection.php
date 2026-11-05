<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Training;

/**
 * Handler for practical training start correction (E3.3 / WebE33C) submissions.
 *
 * Corrects a previous E3.3 declaration, referenced through
 * `setRelatedProtocol()` and `setRelatedDate()` on the declaration.
 */
class PracticalTrainingCorrection extends PracticalTrainingDocument
{
    private const ACTION = 'WebE33C';

    protected function action(): string
    {
        return self::ACTION;
    }
}
