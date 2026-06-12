<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Modification;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\Concerns\TransformsSupplementaryInsurance;
use OxygenSuite\OxygenErgani\Http\Documents\Modification\Concerns\TransformsModificationTypes;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

/**
 * Employment Modification declaration handler for WebMA.
 *
 * Reports modifications to employment terms for regular employees.
 */
class EmploymentModification extends Documents
{
    use TransformsModificationTypes;
    use TransformsSupplementaryInsurance;

    private const ACTION = 'WebMA';

    private const SUPPLEMENTARY_INSURANCE_KEY = 'EpikourikesMA';

    private const MODIFICATION_TYPES_KEY = 'TypesMetabolonMA';

    /**
     * @param ModificationDeclaration|ModificationDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(ModificationDeclaration|array $declarations): array
    {
        if ($declarations instanceof ModificationDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesMA' => [
                'AnaggeliaMA' => array_map(
                    fn(ModificationDeclaration $item) => $this->transformDeclaration($item),
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

    /**
     * @return array<string, mixed>
     */
    protected function transformDeclaration(ModificationDeclaration $declaration): array
    {
        $array = $declaration->toSortedArray();

        // Transform nested arrays
        $array = $this->transformModificationTypeSelections($array, self::MODIFICATION_TYPES_KEY);
        $array = $this->transformSupplementaryInsuranceSelections($array, self::SUPPLEMENTARY_INSURANCE_KEY);

        return $array;
    }
}
