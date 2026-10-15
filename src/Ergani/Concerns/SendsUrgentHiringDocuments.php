<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\UrgentHiring\UrgentHiring;
use OxygenSuite\OxygenErgani\Http\Documents\UrgentHiring\UrgentHiringCorrection;
use OxygenSuite\OxygenErgani\Models\UrgentHiring\UrgentHiringDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsUrgentHiringDocuments
{
    /**
     * Submit urgent hiring declaration (FastAna).
     *
     * @param UrgentHiringDeclaration|UrgentHiringDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendUrgentHiring(UrgentHiringDeclaration|array $declarations): array
    {
        return (new UrgentHiring($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit urgent hiring correction (FastAnaC).
     *
     * @param UrgentHiringDeclaration|UrgentHiringDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendUrgentHiringCorrection(UrgentHiringDeclaration|array $declarations): array
    {
        return (new UrgentHiringCorrection($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
