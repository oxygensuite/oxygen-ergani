<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Termination;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Termination\MandatoryRetirementDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for mandatory retirement (E5DS) submissions.
 *
 * This is used for reporting employee departure due to mandatory retirement
 * (after 15 years of service or reaching age limit) with compensation.
 *
 * @see xsd/E5DS_v1.xsd
 */
class RetirementMandatory extends Documents
{
    private const ACTION = 'WebE5DS';

    /**
     * @param MandatoryRetirementDeclaration|MandatoryRetirementDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(MandatoryRetirementDeclaration|array $declarations): array
    {
        if ($declarations instanceof MandatoryRetirementDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE5DS' => [
                'AnaggeliaE5DS' => array_map(
                    fn(MandatoryRetirementDeclaration $item) => $item->toSortedArray(),
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
