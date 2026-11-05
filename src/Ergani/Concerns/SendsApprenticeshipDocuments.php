<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Apprenticeship\Apprenticeship;
use OxygenSuite\OxygenErgani\Http\Documents\Apprenticeship\ApprenticeshipCorrection;
use OxygenSuite\OxygenErgani\Models\Apprenticeship\ApprenticeshipDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsApprenticeshipDocuments
{
    /**
     * Submit apprenticeship start declaration (E3.4).
     *
     * @param ApprenticeshipDeclaration|ApprenticeshipDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendApprenticeship(ApprenticeshipDeclaration|array $declarations): array
    {
        return (new Apprenticeship($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit apprenticeship start correction (E3.4 correction).
     *
     * @param ApprenticeshipDeclaration|ApprenticeshipDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendApprenticeshipCorrection(ApprenticeshipDeclaration|array $declarations): array
    {
        return (new ApprenticeshipCorrection($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
