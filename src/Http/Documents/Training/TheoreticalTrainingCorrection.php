<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Training;

/**
 * Handler for theoretical training start correction (E3.2 / WebE32C) submissions.
 *
 * Corrects a previous E3.2 declaration, referenced through
 * `setRelatedProtocol()` and `setRelatedDate()` on the declaration.
 */
class TheoreticalTrainingCorrection extends TheoreticalTrainingDocument
{
    private const ACTION = 'WebE32C';

    protected function action(): string
    {
        return self::ACTION;
    }
}
