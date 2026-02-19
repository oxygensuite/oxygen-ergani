<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Internship;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for internship declaration (E3.5 / 57) submissions.
 *
 * Used for reporting internship start or modification to ERGANI.
 *
 * @see xsd/E35_v2.xsd
 */
class Internship extends Documents
{
    private const ACTION = '57';

    /**
     * @param InternshipDeclaration|InternshipDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(InternshipDeclaration|array $declarations): array
    {
        if ($declarations instanceof InternshipDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'PraktikesE35' => [
                'PraktikhE35' => array_map(
                    fn(InternshipDeclaration $item) => $item->toSortedArray(),
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
