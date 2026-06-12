<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Termination;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Termination\NotificationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for resignation notification (E5O) submissions.
 *
 * This is used to notify about possible voluntary resignation
 * when an employee is absent without justification.
 *
 * @see xsd/E5O_v1.xsd
 */
class ResignationNotification extends Documents
{
    private const ACTION = 'WebE5O';

    /**
     * @param NotificationDeclaration|NotificationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(NotificationDeclaration|array $declarations): array
    {
        if ($declarations instanceof NotificationDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE5O' => [
                'AnaggeliaE5O' => array_map(
                    fn(NotificationDeclaration $item) => $item->toSortedArray(),
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
