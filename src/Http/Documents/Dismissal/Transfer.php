<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Dismissal\TransferDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for employee transfer to another company (E6M) submissions.
 *
 * Used when an employee is transferred to another company.
 * This is the simplest E6 form - only requires basic employee info
 * and transfer details (date and receiving company).
 *
 * @see xsd/E6M_v1.xsd
 */
class Transfer extends Documents
{
    private const ACTION = 'WebE6M';

    /**
     * @param TransferDeclaration|TransferDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(TransferDeclaration|array $declarations): array
    {
        if ($declarations instanceof TransferDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE6M' => [
                'AnaggeliaE6M' => array_map(
                    fn(TransferDeclaration $item) => $item->toSortedArray(),
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
