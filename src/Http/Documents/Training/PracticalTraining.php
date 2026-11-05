<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Training;

/**
 * Handler for practical training start (E3.3 / WebE33) submissions.
 *
 * Announces the start of the practical training that follows a theoretical training.
 */
class PracticalTraining extends PracticalTrainingDocument
{
    private const ACTION = 'WebE33';

    protected function action(): string
    {
        return self::ACTION;
    }
}
