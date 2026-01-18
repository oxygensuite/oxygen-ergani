<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Hiring;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\Concerns\TransformsSupplementaryInsurance;
use OxygenSuite\OxygenErgani\Models\Hiring\LendingDeclaration;
use OxygenSuite\OxygenErgani\Responses\SubmissionResponse;

class HiringWithLending extends Documents
{
    use TransformsSupplementaryInsurance;

    private const ACTION = 'WebE3PD';

    private const SUPPLEMENTARY_INSURANCE_KEY = 'EpikourikiSelectionsE3PD';

    /**
     * @param LendingDeclaration|LendingDeclaration[] $declarations
     *
     * @return SubmissionResponse[]
     * @throws ErganiException
     */
    public function handle(LendingDeclaration|array $declarations): array
    {
        if ($declarations instanceof LendingDeclaration) {
            $declarations = [$declarations];
        }

        $body = [
            'AnaggeliesE3PD' => [
                'AnaggeliaE3PD' => array_map(
                    fn(LendingDeclaration $item) => $this->transformDeclaration($item),
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
    protected function transformDeclaration(LendingDeclaration $declaration): array
    {
        return $this->transformSupplementaryInsuranceSelections(
            $declaration->toSortedArray(),
            self::SUPPLEMENTARY_INSURANCE_KEY,
        );
    }
}
