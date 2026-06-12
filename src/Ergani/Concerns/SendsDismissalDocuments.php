<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithNotice;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithoutNotice;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\EndOfLoan;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\RetirementDismissal;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\Transfer;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\TrialPeriodTermination;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithNoticeDeclaration;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithoutNoticeDeclaration;
use OxygenSuite\OxygenErgani\Models\Dismissal\EndOfLoanDeclaration;
use OxygenSuite\OxygenErgani\Models\Dismissal\RetirementDismissalDeclaration;
use OxygenSuite\OxygenErgani\Models\Dismissal\TransferDeclaration;
use OxygenSuite\OxygenErgani\Models\Dismissal\TrialPeriodTerminationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

trait SendsDismissalDocuments
{
    /**
     * Submit dismissal without notice declaration (E6NXP).
     *
     * Used for employer-initiated immediate terminations.
     *
     * @param DismissalWithoutNoticeDeclaration|DismissalWithoutNoticeDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendDismissalWithoutNotice(DismissalWithoutNoticeDeclaration|array $declarations): array
    {
        return (new DismissalWithoutNotice($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit dismissal with notice declaration (E6NMP).
     *
     * Used for employer-initiated terminations with advance notice period.
     *
     * @param DismissalWithNoticeDeclaration|DismissalWithNoticeDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendDismissalWithNotice(DismissalWithNoticeDeclaration|array $declarations): array
    {
        return (new DismissalWithNotice($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit retirement dismissal declaration (E6SXP).
     *
     * Used when employer terminates contract for employee retirement.
     *
     * @param RetirementDismissalDeclaration|RetirementDismissalDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendRetirementDismissal(RetirementDismissalDeclaration|array $declarations): array
    {
        return (new RetirementDismissal($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit end of employee loan declaration (E6LD).
     *
     * Used when a loaned employee returns to their original employer.
     *
     * @param EndOfLoanDeclaration|EndOfLoanDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendEndOfLoan(EndOfLoanDeclaration|array $declarations): array
    {
        return (new EndOfLoan($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit trial period termination declaration (E6LT).
     *
     * Used when employment automatically terminates at end of trial period.
     *
     * @param TrialPeriodTerminationDeclaration|TrialPeriodTerminationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendTrialPeriodTermination(TrialPeriodTerminationDeclaration|array $declarations): array
    {
        return (new TrialPeriodTermination($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }

    /**
     * Submit employee transfer declaration (E6M).
     *
     * Used when an employee is transferred to another company.
     *
     * @param TransferDeclaration|TransferDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function sendTransfer(TransferDeclaration|array $declarations): array
    {
        return (new Transfer($this->accessToken, $this->environment, $this->config))
            ->handle($declarations);
    }
}
