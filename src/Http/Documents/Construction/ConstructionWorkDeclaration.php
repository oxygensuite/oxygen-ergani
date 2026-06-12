<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Construction;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionWork;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for construction work personnel declaration (E12) submissions.
 *
 * Used for reporting daily construction site personnel to ERGANI.
 *
 * @see xsd/E12_v1.xsd
 */
class ConstructionWorkDeclaration extends Documents
{
    private const ACTION = 'E12';

    /**
     * @param ConstructionWork|ConstructionWork[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(ConstructionWork|array $declarations): array
    {
        if ($declarations instanceof ConstructionWork) {
            $declarations = [$declarations];
        }

        $body = [
            'Amoes' => [
                'Amoe' => array_map(
                    fn(ConstructionWork $item) => $item->toSortedArray(),
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
