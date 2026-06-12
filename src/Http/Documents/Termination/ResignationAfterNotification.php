<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Termination;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Termination\ResignationAfterNotificationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for resignation after notification (E5AO) submissions.
 *
 * This is used when confirming a resignation that follows
 * a previous E5O notification submission.
 *
 * @see xsd/E5AO_v1.xsd
 */
class ResignationAfterNotification extends Documents
{
    private const ACTION = 'WebE5AO';

    /**
     * @param ResignationAfterNotificationDeclaration|ResignationAfterNotificationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(ResignationAfterNotificationDeclaration|array $declarations): array
    {
        if ($declarations instanceof ResignationAfterNotificationDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE5AO' => [
                'AnaggeliaE5AO' => array_map(
                    fn(ResignationAfterNotificationDeclaration $item) => $item->toSortedArray(),
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
