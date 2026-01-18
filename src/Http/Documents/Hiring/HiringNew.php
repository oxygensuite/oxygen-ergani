<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Hiring;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\Concerns\TransformsSupplementaryInsurance;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

class HiringNew extends Documents
{
    use TransformsSupplementaryInsurance;

    private const ACTION = 'WebE3N';

    private const SUPPLEMENTARY_INSURANCE_KEY = 'EpikourikiSelectionsE3N';

    /**
     * @param NewDeclaration|NewDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(NewDeclaration|array $declarations): array
    {
        if ($declarations instanceof NewDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE3N' => [
                'AnaggeliaE3N' => array_map(
                    fn(NewDeclaration $item) => $this->transformDeclaration($item),
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
    protected function transformDeclaration(NewDeclaration $declaration): array
    {
        return $this->transformSupplementaryInsuranceSelections(
            $declaration->toSortedArray(),
            self::SUPPLEMENTARY_INSURANCE_KEY,
        );
    }
}
