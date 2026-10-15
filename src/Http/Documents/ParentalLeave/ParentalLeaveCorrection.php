<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave;

/**
 * Handler for parental leave correction (E14 / WebE14C) submissions.
 *
 * Corrects or changes a previous E14 declaration, referenced through
 * `setRelatedProtocol()` and `setRelatedDate()` on the declaration.
 */
class ParentalLeaveCorrection extends ParentalLeaveDocument
{
    private const ACTION = 'WebE14C';

    protected function action(): string
    {
        return self::ACTION;
    }
}
