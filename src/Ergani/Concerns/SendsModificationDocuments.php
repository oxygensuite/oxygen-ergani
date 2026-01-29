<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Modification\BorrowedEmploymentModification;
use OxygenSuite\OxygenErgani\Http\Documents\Modification\EmploymentModification;
use OxygenSuite\OxygenErgani\Models\Modification\BorrowedModificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsModificationDocuments
{
    /**
     * Submit employment modification declaration (WebMA).
     *
     * Reports modifications to employment terms for regular employees.
     *
     * @param ModificationDeclaration|ModificationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendEmploymentModification(ModificationDeclaration|array $declarations): array
    {
        return (new EmploymentModification($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit borrowed employment modification declaration (WebMAD).
     *
     * Reports modifications to employment terms for loaned/borrowed employees.
     *
     * @param BorrowedModificationDeclaration|BorrowedModificationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendBorrowedEmploymentModification(BorrowedModificationDeclaration|array $declarations): array
    {
        return (new BorrowedEmploymentModification($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
