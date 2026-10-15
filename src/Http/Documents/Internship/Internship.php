<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Internship;

/**
 * Handler for internship declaration (E3.5 / WebE35) submissions.
 *
 * Used for reporting internship start or modification to ERGANI.
 *
 * @see xsd/E35_v3.xsd
 */
class Internship extends InternshipDocument
{
    private const ACTION = 'WebE35';

    protected function action(): string
    {
        return self::ACTION;
    }
}
