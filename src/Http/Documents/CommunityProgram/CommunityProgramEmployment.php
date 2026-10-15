<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram;

/**
 * Handler for community-benefit program employment (E3.1 / WebE31) submissions.
 *
 * Announces the employment of a beneficiary of a community-benefit program.
 */
class CommunityProgramEmployment extends CommunityProgramDocument
{
    private const ACTION = 'WebE31';

    protected function action(): string
    {
        return self::ACTION;
    }
}
