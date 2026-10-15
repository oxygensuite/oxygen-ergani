<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Motorcycle;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleUseDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Handler for motorcycle use declaration (E13 / WebE13) submissions.
 *
 * Used for declaring that employees use a moped or motorcycle while working.
 *
 * @see xsd/E13_v1.xsd
 */
class MotorcycleUse extends Documents
{
    private const ACTION = 'WebE13';

    /**
     * @param MotorcycleUseDeclaration|MotorcycleUseDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(MotorcycleUseDeclaration|array $declarations): array
    {
        if ($declarations instanceof MotorcycleUseDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'MsE13' => [
                'ME13' => array_map(
                    fn(MotorcycleUseDeclaration $item) => $item->toSortedArray(),
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
