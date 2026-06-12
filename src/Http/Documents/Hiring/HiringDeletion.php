<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Hiring;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Hiring\DeletionDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

class HiringDeletion extends Documents
{
    private const ACTION = 'WebE3D';

    /**
     * @param DeletionDeclaration|DeletionDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(DeletionDeclaration|array $declarations): array
    {
        if ($declarations instanceof DeletionDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE3D' => [
                'AnaggeliaE3D' => array_map(
                    fn(DeletionDeclaration $item) => $item->toSortedArray(),
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
