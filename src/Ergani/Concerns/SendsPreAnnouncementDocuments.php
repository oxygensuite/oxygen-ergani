<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\PreAnnouncement\PreAnnouncementExemption;
use OxygenSuite\OxygenErgani\Models\PreAnnouncement\ExemptionDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsPreAnnouncementDocuments
{
    /**
     * Submit pre-announcement exemption declaration.
     *
     * @param ExemptionDeclaration|ExemptionDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendPreAnnouncementExemption(ExemptionDeclaration|array $declarations): array
    {
        return (new PreAnnouncementExemption($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
