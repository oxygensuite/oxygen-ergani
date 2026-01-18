<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Hiring;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\Concerns\TransformsSupplementaryInsurance;
use OxygenSuite\OxygenErgani\Models\Hiring\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

class HiringModification extends Documents
{
    use TransformsSupplementaryInsurance;

    private const ACTION = 'WebE3M';

    private const SUPPLEMENTARY_INSURANCE_KEY = 'EpikourikiSelectionsE3M';

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
            'AnaggeliesE3M' => [
                'AnaggeliaE3M' => array_map(
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
        return $this->transformSupplementaryInsuranceSelections(
            $declaration->toSortedArray(),
            self::SUPPLEMENTARY_INSURANCE_KEY,
        );
    }
}
