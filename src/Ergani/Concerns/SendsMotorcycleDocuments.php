<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Motorcycle\MotorcycleUse;
use OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleUseDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsMotorcycleDocuments
{
    /**
     * Submit motorcycle use declaration (E13).
     *
     * @param MotorcycleUseDeclaration|MotorcycleUseDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendMotorcycleUse(MotorcycleUseDeclaration|array $declarations): array
    {
        return (new MotorcycleUse($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
