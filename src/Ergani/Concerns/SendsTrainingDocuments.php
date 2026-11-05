<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Training\PracticalTraining;
use OxygenSuite\OxygenErgani\Http\Documents\Training\PracticalTrainingCorrection;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTraining;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTrainingCorrection;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTrainingEmployerChange;
use OxygenSuite\OxygenErgani\Http\Documents\Training\TheoreticalTrainingInterruption;
use OxygenSuite\OxygenErgani\Models\Training\PracticalTrainingDeclaration;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingDeclaration;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingEmployerChangeDeclaration;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingInterruptionDeclaration;
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

    /**
     * Submit theoretical training employer change (E3.2 employer change).
     *
     * @param TheoreticalTrainingEmployerChangeDeclaration|TheoreticalTrainingEmployerChangeDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendTheoreticalTrainingEmployerChange(TheoreticalTrainingEmployerChangeDeclaration|array $declarations): array
    {
        return (new TheoreticalTrainingEmployerChange($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit theoretical training interruption (E3.2 interruption).
     *
     * @param TheoreticalTrainingInterruptionDeclaration|TheoreticalTrainingInterruptionDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendTheoreticalTrainingInterruption(TheoreticalTrainingInterruptionDeclaration|array $declarations): array
    {
        return (new TheoreticalTrainingInterruption($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit practical training start declaration (E3.3).
     *
     * @param PracticalTrainingDeclaration|PracticalTrainingDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendPracticalTraining(PracticalTrainingDeclaration|array $declarations): array
    {
        return (new PracticalTraining($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit practical training start correction (E3.3 correction).
     *
     * @param PracticalTrainingDeclaration|PracticalTrainingDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendPracticalTrainingCorrection(PracticalTrainingDeclaration|array $declarations): array
    {
        return (new PracticalTrainingCorrection($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
