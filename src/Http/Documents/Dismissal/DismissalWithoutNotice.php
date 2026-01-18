<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithoutNoticeDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for dismissal without notice (E6NXP) submissions.
 *
 * Used for employer-initiated immediate terminations (no notice period).
 *
 * @see xsd/E6NXP_v1.xsd
 */
class DismissalWithoutNotice extends Documents
{
    private const ACTION = 'WebE6NXP';

    /**
     * @param DismissalWithoutNoticeDeclaration|DismissalWithoutNoticeDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(DismissalWithoutNoticeDeclaration|array $declarations): array
    {
        if ($declarations instanceof DismissalWithoutNoticeDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE6NXP' => [
                'AnaggeliaE6NXP' => array_map(
                    fn(DismissalWithoutNoticeDeclaration $item) => $item->toSortedArray(),
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
