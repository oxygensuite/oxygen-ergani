<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramEmployment;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramEmploymentCorrection;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramExtension;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramInterruption;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramDeclaration;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramExtensionDeclaration;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramInterruptionDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsCommunityProgramDocuments
{
    /**
     * Submit community-benefit program employment declaration (E3.1).
     *
     * @param CommunityProgramDeclaration|CommunityProgramDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendCommunityProgramEmployment(CommunityProgramDeclaration|array $declarations): array
    {
        return (new CommunityProgramEmployment($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit community-benefit program employment correction (E3.1 correction).
     *
     * @param CommunityProgramDeclaration|CommunityProgramDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendCommunityProgramEmploymentCorrection(CommunityProgramDeclaration|array $declarations): array
    {
        return (new CommunityProgramEmploymentCorrection($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit community-benefit program employment extension (E3.1 extension).
     *
     * @param CommunityProgramExtensionDeclaration|CommunityProgramExtensionDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendCommunityProgramExtension(CommunityProgramExtensionDeclaration|array $declarations): array
    {
        return (new CommunityProgramExtension($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit community-benefit program employment interruption (E3.1 interruption).
     *
     * @param CommunityProgramInterruptionDeclaration|CommunityProgramInterruptionDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendCommunityProgramInterruption(CommunityProgramInterruptionDeclaration|array $declarations): array
    {
        return (new CommunityProgramInterruption($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
