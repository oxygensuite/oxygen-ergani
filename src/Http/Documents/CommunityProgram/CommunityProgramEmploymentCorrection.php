<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram;

/**
 * Handler for community-benefit program employment correction (E3.1 / WebE31C) submissions.
 *
 * Corrects a previous E3.1 declaration, referenced through
 * `setRelatedProtocol()` and `setRelatedDate()` on the declaration.
 */
class CommunityProgramEmploymentCorrection extends CommunityProgramDocument
{
    private const ACTION = 'WebE31C';

    protected function action(): string
    {
        return self::ACTION;
    }
}
