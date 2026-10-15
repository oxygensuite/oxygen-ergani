<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Internship;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipScheduleChangeDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for internship schedule change (E3.5 time organisation change / WebE35T) submissions.
 *
 * Used for reporting a change to the weekly schedule of an internship
 * declared with a previous E3.5.
 *
 * @see xsd/E35T_v1.xsd
 */
class InternshipScheduleChange extends Documents
{
    private const ACTION = 'WebE35T';

    /**
     * @param InternshipScheduleChangeDeclaration|InternshipScheduleChangeDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(InternshipScheduleChangeDeclaration|array $declarations): array
    {
        if ($declarations instanceof InternshipScheduleChangeDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'PraktikesE35T' => [
                'PraktikhE35T' => array_map(
                    fn(InternshipScheduleChangeDeclaration $item) => $item->toSortedArray(),
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
