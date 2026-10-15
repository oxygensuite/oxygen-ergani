<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramEmployment;
use OxygenSuite\OxygenErgani\Http\Documents\CommunityProgram\CommunityProgramEmploymentCorrection;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramDeclaration;
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
}
