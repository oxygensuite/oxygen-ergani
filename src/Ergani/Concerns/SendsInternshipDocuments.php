<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\Internship;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipCorrection;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipScheduleChange;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipTermination;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipScheduleChangeDeclaration;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipTerminationDeclaration;
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

    /**
     * Submit internship declaration correction (E3.5 correction).
     *
     * @param InternshipDeclaration|InternshipDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendInternshipCorrection(InternshipDeclaration|array $declarations): array
    {
        return (new InternshipCorrection($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit internship termination (E3.5 stop).
     *
     * @param InternshipTerminationDeclaration|InternshipTerminationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendInternshipTermination(InternshipTerminationDeclaration|array $declarations): array
    {
        return (new InternshipTermination($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit internship schedule change (E3.5 time organisation change).
     *
     * @param InternshipScheduleChangeDeclaration|InternshipScheduleChangeDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendInternshipScheduleChange(InternshipScheduleChangeDeclaration|array $declarations): array
    {
        return (new InternshipScheduleChange($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
