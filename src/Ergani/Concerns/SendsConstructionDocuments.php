<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Construction\ConstructionWorkCensus;
use OxygenSuite\OxygenErgani\Http\Documents\Construction\ConstructionWorkDeclaration;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionCensus;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionWork;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsConstructionDocuments
{
    /**
     * Submit construction work personnel declaration (E12).
     *
     * @param ConstructionWork|ConstructionWork[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendConstructionWork(ConstructionWork|array $declarations): array
    {
        return (new ConstructionWorkDeclaration($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit construction work census declaration (E12Apogr).
     *
     * @param ConstructionCensus|ConstructionCensus[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendConstructionWorkCensus(ConstructionCensus|array $declarations): array
    {
        return (new ConstructionWorkCensus($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
