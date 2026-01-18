<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Dismissal\EndOfLoanDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for end of employee loan (E6LD) submissions.
 *
 * Used when a loaned employee returns to their original employer.
 * Does NOT include salary, severance, or form file - loan termination
 * simply returns the employee to the original employer.
 *
 * @see xsd/E6LD_v1.xsd
 */
class EndOfLoan extends Documents
{
    private const ACTION = 'WebE6LD';

    /**
     * @param EndOfLoanDeclaration|EndOfLoanDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(EndOfLoanDeclaration|array $declarations): array
    {
        if ($declarations instanceof EndOfLoanDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE6LD' => [
                'AnaggeliaE6LD' => array_map(
                    fn(EndOfLoanDeclaration $item) => $item->toSortedArray(),
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
