<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Termination;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryRetirementDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for voluntary retirement (E5S) submissions.
 *
 * This is used for reporting employee departure due to voluntary retirement
 * with compensation/severance pay.
 *
 * @see xsd/E5S_v1.xsd
 */
class RetirementVoluntary extends Documents
{
    private const ACTION = 'WebE5S';

    /**
     * @param VoluntaryRetirementDeclaration|VoluntaryRetirementDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(VoluntaryRetirementDeclaration|array $declarations): array
    {
        if ($declarations instanceof VoluntaryRetirementDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE5S' => [
                'AnaggeliaE5S' => array_map(
                    fn(VoluntaryRetirementDeclaration $item) => $item->toSortedArray(),
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
