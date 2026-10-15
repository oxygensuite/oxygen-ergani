<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave\ParentalLeave;
use OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave\ParentalLeaveCorrection;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsParentalLeaveDocuments
{
    /**
     * Submit parental leave declaration (E14).
     *
     * @param ParentalLeaveDeclaration|ParentalLeaveDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendParentalLeave(ParentalLeaveDeclaration|array $declarations): array
    {
        return (new ParentalLeave($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit parental leave correction (E14 correction / change).
     *
     * @param ParentalLeaveDeclaration|ParentalLeaveDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendParentalLeaveCorrection(ParentalLeaveDeclaration|array $declarations): array
    {
        return (new ParentalLeaveCorrection($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
