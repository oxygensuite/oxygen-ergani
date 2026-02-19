<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\SixthDay\SixthDay;
use OxygenSuite\OxygenErgani\Models\SixthDay\SixthDayDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsSixthDayDocuments
{
    /**
     * Submit sixth day / extra shift declaration.
     *
     * @param SixthDayDeclaration|SixthDayDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendSixthDayDeclaration(SixthDayDeclaration|array $declarations): array
    {
        return (new SixthDay($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
