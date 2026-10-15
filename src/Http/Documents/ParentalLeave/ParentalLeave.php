<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave;

/**
 * Handler for parental leave declaration (E14 / WebE14) submissions.
 *
 * Used for declaring parental leave granted under article 28 of Law 4808/2021.
 */
class ParentalLeave extends ParentalLeaveDocument
{
    private const ACTION = 'WebE14';

    protected function action(): string
    {
        return self::ACTION;
    }
}
