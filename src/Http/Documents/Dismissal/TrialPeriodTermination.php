<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Dismissal\TrialPeriodTerminationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for trial period termination (E6LT) submissions.
 *
 * Used when employment automatically terminates at the end of trial period.
 * Does NOT include severance pay - trial period termination requires no compensation.
 *
 * @see xsd/E6LT_v1.xsd
 */
class TrialPeriodTermination extends Documents
{
    private const ACTION = 'WebE6LT';

    /**
     * @param TrialPeriodTerminationDeclaration|TrialPeriodTerminationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(TrialPeriodTerminationDeclaration|array $declarations): array
    {
        if ($declarations instanceof TrialPeriodTerminationDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE6LT' => [
                'AnaggeliaE6LT' => array_map(
                    fn(TrialPeriodTerminationDeclaration $item) => $item->toSortedArray(),
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
