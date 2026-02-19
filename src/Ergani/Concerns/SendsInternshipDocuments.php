<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\Internship;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsInternshipDocuments
{
    /**
     * Submit internship declaration (E3.5).
     *
     * @param InternshipDeclaration|InternshipDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendInternshipDeclaration(InternshipDeclaration|array $declarations): array
    {
        return (new Internship($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
