<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringDeletion;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringModification;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringWithLending;
use OxygenSuite\OxygenErgani\Models\Hiring\DeletionDeclaration;
use OxygenSuite\OxygenErgani\Models\Hiring\LendingDeclaration;
use OxygenSuite\OxygenErgani\Models\Hiring\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsHiringDocuments
{
    /**
     * Submit new employee hiring declaration (E3N).
     *
     * @param NewDeclaration|NewDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendHiringNew(NewDeclaration|array $declarations): array
    {
        return (new HiringNew($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit employee transfer declaration (E3M).
     *
     * Used when an employee transfers from another company.
     *
     * @param ModificationDeclaration|ModificationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendHiringModification(ModificationDeclaration|array $declarations): array
    {
        return (new HiringModification($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit employee lending FROM direct employer declaration (E3D).
     *
     * Used when lending an employee to another company.
     *
     * @param DeletionDeclaration|DeletionDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendHiringDeletion(DeletionDeclaration|array $declarations): array
    {
        return (new HiringDeletion($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit employee hiring TO indirect employer declaration (E3PD).
     *
     * Used when hiring an employee from a lending arrangement.
     *
     * @param LendingDeclaration|LendingDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendHiringWithLending(LendingDeclaration|array $declarations): array
    {
        return (new HiringWithLending($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
