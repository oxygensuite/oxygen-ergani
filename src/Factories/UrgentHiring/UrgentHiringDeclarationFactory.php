<?php

namespace OxygenSuite\OxygenErgani\Factories\UrgentHiring;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Hiring\SupplementaryInsuranceSelectionFactory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Factory for creating UrgentHiringDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\UrgentHiring\UrgentHiringDeclaration>
 */
class UrgentHiringDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for UrgentHiringDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $startTime = $fake->time24h();

        return [
            'f_aa_pararthmatos' => '0',
            'f_rel_protocol' => '',
            'f_rel_date' => '',
            'f_kad_pararthmatos' => (string) $fake->numberBetween(1000, 9999),
            'f_kallikratis_pararthmatos' => (string) $fake->numberBetween(10000000, 99999999),
            'f_afm' => $fake->afm(),
            'f_til' => '69' . $fake->numberBetween(10000000, 99999999),
            'f_proslipsidate' => date('d/m/Y'),
            'f_proslipsitime' => $startTime,
            'f_apoxwrisitime' => $fake->workEndTime($startTime, 4),
            'f_second_working_date' => '',
            'f_second_working_datetime' => '',
            'second_working_datetime_apoxwrisis' => '',
            'f_eidikothta' => (string) $fake->numberBetween(100000, 999999),
            'f_eidikothta_anal' => '',
            'f_proipiresia' => $fake->numberBetween(0, 20),
            'f_apodoxes' => (float) $fake->numberBetween(40, 200),
            'f_xronos_katavolis_apodoxon' => '1',
            'f_topos_ergasias' => '0',
            'f_topos_ergasias_comment' => '',
            'f_xaraktirismos' => '0',
            'f_efarmostea_sillogiki_simbasi' => '0',
            'f_efarmostea_sillogiki_simbasi_comments' => '',
            'f_kyria_asfalisi' => '001',
            'EpikourikiSelections' => ['EpikourikiSelectionsFastAna' => []],
            'f_week_days' => '5',
            'f_dialeimma_minutes' => 0,
            'f_dialeimma_entos_wrariou' => '0',
            'f_euelikto_wrario_minutes' => 0,
            'f_comments' => '',
        ];
    }

    /**
     * Configure for a specific branch.
     */
    public function forBranch(int|string $branchCode): static
    {
        return $this->state([
            'f_aa_pararthmatos' => (string) $branchCode,
        ]);
    }

    /**
     * Configure as a correction to a previous submission.
     */
    public function asCorrection(string $protocol, string $date): static
    {
        return $this->state([
            'f_rel_protocol' => $protocol,
            'f_rel_date' => $date,
        ]);
    }

    /**
     * Configure with a second working day.
     */
    public function withSecondWorkingDay(string $date, string $startTime = '09:00', string $endTime = '13:00'): static
    {
        return $this->state([
            'f_second_working_date' => $date,
            'f_second_working_datetime' => $startTime,
            'second_working_datetime_apoxwrisis' => $endTime,
        ]);
    }

    /**
     * Configure as a white-collar employee.
     */
    public function asEmployee(): static
    {
        return $this->state([
            'f_xaraktirismos' => '1',
        ]);
    }

    /**
     * Configure with an applicable collective agreement.
     */
    public function withCollectiveAgreement(string $comment = ''): static
    {
        return $this->state([
            'f_efarmostea_sillogiki_simbasi' => '1',
            'f_efarmostea_sillogiki_simbasi_comments' => $comment,
        ]);
    }

    /**
     * Configure with supplementary insurance codes.
     *
     * @param array<int, string> $codes
     */
    public function withSupplementaryInsurance(array $codes = ['001']): static
    {
        return $this->state([
            'EpikourikiSelections' => fn() => [
                'EpikourikiSelectionsFastAna' => array_map(
                    fn(string $code) => self::many(SupplementaryInsuranceSelectionFactory::new()->code($code)->make())[0],
                    $codes,
                ),
            ],
        ]);
    }

    /**
     * Normalize a factory result to a list of models.
     *
     * @param Model|array<int, Model> $made
     *
     * @return array<int, Model>
     */
    private static function many(Model|array $made): array
    {
        return is_array($made) ? $made : [$made];
    }
}
