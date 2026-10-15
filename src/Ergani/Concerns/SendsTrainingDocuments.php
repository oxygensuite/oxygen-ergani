<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTraining;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTrainingCorrection;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsTrainingDocuments
{
    /**
     * Submit theoretical training start declaration (E3.2).
     *
     * @param TheoreticalTrainingDeclaration|TheoreticalTrainingDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendTheoreticalTraining(TheoreticalTrainingDeclaration|array $declarations): array
    {
        return (new TheoreticalTraining($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit theoretical training start correction (E3.2 correction).
     *
     * @param TheoreticalTrainingDeclaration|TheoreticalTrainingDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendTheoreticalTrainingCorrection(TheoreticalTrainingDeclaration|array $declarations): array
    {
        return (new TheoreticalTrainingCorrection($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
