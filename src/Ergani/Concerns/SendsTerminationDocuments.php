<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\FixedTermTermination;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationAfterNotification;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationNotification;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\RetirementMandatory;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\RetirementVoluntary;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\TerminationByDeath;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryExitCompensation;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryResignation;
use OxygenSuite\OxygenErgani\Models\Termination\CompensatedExitDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\DeathTerminationDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\FixedTermTerminationDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\MandatoryRetirementDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\NotificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\ResignationAfterNotificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryResignationDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryRetirementDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsTerminationDocuments
{
    /**
     * Submit voluntary resignation declaration (E5N).
     *
     * @param VoluntaryResignationDeclaration|VoluntaryResignationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendVoluntaryResignation(VoluntaryResignationDeclaration|array $declarations): array
    {
        return (new VoluntaryResignation($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit resignation notification declaration (E5O).
     *
     * Used to notify about possible voluntary resignation when an employee is absent.
     *
     * @param NotificationDeclaration|NotificationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendResignationNotification(NotificationDeclaration|array $declarations): array
    {
        return (new ResignationNotification($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit resignation after notification declaration (E5AO).
     *
     * Used when confirming a resignation that follows a previous E5O notification.
     *
     * @param ResignationAfterNotificationDeclaration|ResignationAfterNotificationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendResignationAfterNotification(ResignationAfterNotificationDeclaration|array $declarations): array
    {
        return (new ResignationAfterNotification($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit termination by death declaration (E5D).
     *
     * @param DeathTerminationDeclaration|DeathTerminationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendTerminationByDeath(DeathTerminationDeclaration|array $declarations): array
    {
        return (new TerminationByDeath($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit voluntary exit with compensation declaration (E5E).
     *
     * Used for voluntary separation programs with severance pay.
     *
     * @param CompensatedExitDeclaration|CompensatedExitDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendVoluntaryExitCompensation(CompensatedExitDeclaration|array $declarations): array
    {
        return (new VoluntaryExitCompensation($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit voluntary retirement declaration (E5S).
     *
     * @param VoluntaryRetirementDeclaration|VoluntaryRetirementDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendRetirementVoluntary(VoluntaryRetirementDeclaration|array $declarations): array
    {
        return (new RetirementVoluntary($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit mandatory retirement declaration (E5DS).
     *
     * Used when employee retirement is mandatory (15 years service or age limit).
     *
     * @param MandatoryRetirementDeclaration|MandatoryRetirementDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendRetirementMandatory(MandatoryRetirementDeclaration|array $declarations): array
    {
        return (new RetirementMandatory($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit fixed-term contract termination declaration (E7N).
     *
     * @param FixedTermTerminationDeclaration|FixedTermTerminationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendFixedTermTermination(FixedTermTerminationDeclaration|array $declarations): array
    {
        return (new FixedTermTermination($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
