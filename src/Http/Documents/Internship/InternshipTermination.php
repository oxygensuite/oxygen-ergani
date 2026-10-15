<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Internship;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipTerminationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for internship termination (E3.5 stop / WebE35D) submissions.
 *
 * Used for reporting the early termination (interruption) of an internship
 * declared with a previous E3.5.
 *
 * @see xsd/E35D_v1.xsd
 */
class InternshipTermination extends Documents
{
    private const ACTION = 'WebE35D';

    /**
     * @param InternshipTerminationDeclaration|InternshipTerminationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(InternshipTerminationDeclaration|array $declarations): array
    {
        if ($declarations instanceof InternshipTerminationDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'PraktikesE35D' => [
                'PraktikhE35D' => array_map(
                    fn(InternshipTerminationDeclaration $item) => $item->toSortedArray(),
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
