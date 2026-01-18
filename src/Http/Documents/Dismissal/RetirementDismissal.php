<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Dismissal\RetirementDismissalDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for retirement dismissal (E6SXP) submissions.
 *
 * Used when employer terminates contract for employee retirement.
 * Does NOT include collective dismissal fields - retirement dismissals are individual.
 *
 * @see xsd/E6SXP_v1.xsd
 */
class RetirementDismissal extends Documents
{
    private const ACTION = 'WebE6SXP';

    /**
     * @param RetirementDismissalDeclaration|RetirementDismissalDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(RetirementDismissalDeclaration|array $declarations): array
    {
        if ($declarations instanceof RetirementDismissalDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE6SXP' => [
                'AnaggeliaE6SXP' => array_map(
                    fn(RetirementDismissalDeclaration $item) => $item->toSortedArray(),
                    $declarations,
                ),
            ],
        ];

        return $this->submit($body)->morphToArray(SubmissionResponse::class);
    }

    protected function action(): string
    {
        return self::ACTION;
    }
}
