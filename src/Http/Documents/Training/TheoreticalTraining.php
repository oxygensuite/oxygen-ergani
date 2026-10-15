<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Training;

/**
 * Handler for theoretical training start (E3.2 / WebE32) submissions.
 *
 * Announces the start of theoretical training for a program beneficiary.
 */
class TheoreticalTraining extends TheoreticalTrainingDocument
{
    private const ACTION = 'WebE32';

    protected function action(): string
    {
        return self::ACTION;
    }
}
