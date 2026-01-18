<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Modification;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Modification\BorrowedModificationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Borrowed Employment Modification declaration handler for WebMAD.
 *
 * Reports modifications to employment terms for loaned/borrowed employees.
 */
class BorrowedEmploymentModification extends Documents
{
    private const ACTION = 'WebMAD';

    /**
     * @param BorrowedModificationDeclaration|BorrowedModificationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(BorrowedModificationDeclaration|array $declarations): array
    {
        if ($declarations instanceof BorrowedModificationDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesMAD' => [
                'AnaggeliaMAD' => array_map(
                    fn(BorrowedModificationDeclaration $item) => $item->toSortedArray(),
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
