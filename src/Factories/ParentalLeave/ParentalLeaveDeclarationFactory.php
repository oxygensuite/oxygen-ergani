<?php

namespace OxygenSuite\OxygenErgani\Factories\ParentalLeave;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Model;

/**
 * Factory for creating ParentalLeaveDeclaration instances with fake data.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDeclaration>
 */
class ParentalLeaveDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for ParentalLeaveDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();
        $sex = $fake->randomElement(['male', 'female']);

        return [
            'f_aa_pararthmatos' => '0',
            'f_rel_protocol' => '',
            'f_rel_date' => '',
            'f_ypiresia_sepe' => (string) $fake->numberBetween(10000, 99999),
            'f_ypiresia_oaed' => (string) $fake->numberBetween(100000, 999999),
            'f_payment_from_forea' => '0',
            'f_category' => '3', // required when not paid by the entity: private sector employee
            'f_afm_ergazom' => $fake->afm(),
            'f_amka_ergazom' => $fake->amka(),
            'f_am_ika_ergazom' => $fake->amika(),
            'f_eponimo' => $fake->greekLastName(),
            'f_onoma' => $fake->greekFirstName($sex),
            'f_date_proslipsis' => $fake->greekDate('-10 years', '-1 year'),
            'f_better_setting_exists' => '0',
            'f_better_setting_descr' => '',
            'f_kathestos_apasxolisis' => '0',
            'f_sxesh_apasxolisis' => '0',
            'f_week_days' => '5',
            'f_week_hours' => 40.0,
            'f_foreas_kyrias_asfalisis' => 'e-ΕΦΚΑ',
            'f_foreas_epikourikhs_asfalisis' => 'e-ΕΦΚΑ',
            'f_one_parent' => '0',
            'f_one_parent_case' => '',
            'f_tekno_from_polydymh_kyhsh' => '0',
            'f_tropos_xorhghshs_adeias' => '0',
            'f_date_enarxis_adeias' => date('d/m/Y', strtotime('+1 week')),
            'f_date_lhxhs_adeias' => date('d/m/Y', strtotime('+2 weeks')),
            'f_comments' => '',
            'f_afm_proswpoy' => $fake->afm(),
            'Amkas' => fn() => ['AmkasE14' => [ParentalLeaveChildFactory::new()->make()]],
            'Dates' => ['DatesE14' => []],
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
     * Configure as part-time employment.
     */
    public function partTime(float $hours = 20.0): static
    {
        return $this->state([
            'f_kathestos_apasxolisis' => '1',
            'f_week_hours' => $hours,
        ]);
    }

    /**
     * Configure for a single parent.
     */
    public function singleParent(string $caseCode = '001'): static
    {
        return $this->state([
            'f_one_parent' => '1',
            'f_one_parent_case' => $caseCode,
        ]);
    }

    /**
     * Configure as a flexible (partial) leave with the given number of full-day leave dates.
     */
    public function flexible(int $days = 3): static
    {
        return $this->state([
            'f_tropos_xorhghshs_adeias' => '1',
            'Dates' => fn() => ['DatesE14' => self::many(ParentalLeaveDateFactory::new()->count($days)->make())],
        ]);
    }

    /**
     * Configure with the given number of children.
     */
    public function withChildren(int $count = 1): static
    {
        return $this->state([
            'Amkas' => fn() => ['AmkasE14' => self::many(ParentalLeaveChildFactory::new()->count($count)->make())],
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
