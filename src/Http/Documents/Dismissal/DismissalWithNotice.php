<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithNoticeDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for dismissal with notice (E6NMP) submissions.
 *
 * Used for employer-initiated terminations with advance notice period.
 * The employee continues working during the notice period.
 *
 * @see xsd/E6NMP_v1.xsd
 */
class DismissalWithNotice extends Documents
{
    private const ACTION = 'WebE6NMP';

    /**
     * @param DismissalWithNoticeDeclaration|DismissalWithNoticeDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(DismissalWithNoticeDeclaration|array $declarations): array
    {
        if ($declarations instanceof DismissalWithNoticeDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE6NMP' => [
                'AnaggeliaE6NMP' => array_map(
                    fn(DismissalWithNoticeDeclaration $item) => $item->toSortedArray(),
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
