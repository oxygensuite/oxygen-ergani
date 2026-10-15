<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Internship;

/**
 * Handler for internship declaration correction (E3.5 / WebE35C) submissions.
 *
 * Corrects a previous E3.5 declaration, referenced through
 * `setRelatedProtocol()` and `setRelatedDate()` on the declaration.
 *
 * @see xsd/E35_v3.xsd
 */
class InternshipCorrection extends InternshipDocument
{
    private const ACTION = 'WebE35C';

    protected function action(): string
    {
        return self::ACTION;
    }
}
