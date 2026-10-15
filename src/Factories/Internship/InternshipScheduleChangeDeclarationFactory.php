<?php

namespace OxygenSuite\OxygenErgani\Factories\Internship;

use OxygenSuite\OxygenErgani\Factories\Factory;

/**
 * Factory for creating InternshipScheduleChangeDeclaration instances with fake data.
 *
 * Generates a Monday-Friday 09:00-15:00 schedule with every other time field empty.
 *
 * @extends Factory<\OxygenSuite\OxygenErgani\Models\Internship\InternshipScheduleChangeDeclaration>
 */
class InternshipScheduleChangeDeclarationFactory extends Factory
{
    /**
     * Define the default attribute values for InternshipScheduleChangeDeclaration.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fake = self::fake();

        $attributes = [
            'f_aa_pararthmatos' => '0',
            'f_rel_protocol' => 'ΠΡ' . $fake->numberBetween(1, 9999),
            'f_rel_date' => $fake->greekDate('-6 months', '-1 month'),
            'f_afm' => $fake->afm(),
            'f_trpopoihsh_date' => $fake->greekDate('now', '+1 month'),
        ];

        foreach (['f_time', 'f_second_time', 'f_break_time'] as $prefix) {
            for ($day = 1; $day <= 7; $day++) {
                $isWorkday = $prefix === 'f_time' && $day <= 5;
                $attributes["{$prefix}_from_{$day}"] = $isWorkday ? '09:00' : '';
                $attributes["{$prefix}_to_{$day}"] = $isWorkday ? '15:00' : '';
            }
        }

        return $attributes + [
            'f_eponymo_idiotitas' => $fake->greekLastName(),
            'f_onoma_idiotitas' => $fake->greekFirstName(),
            'f_idiotita_idiotitas' => 'ΛΟΓΙΣΤΗΣ',
            'f_dieythinsi_idiotitas' => 'ΑΘΗΝΑ',
            'f_afm_idiotitas' => $fake->afm(),
            'f_afm_proswpoy' => $fake->afm(),
            'f_file' => '',
            'f_foreign_file' => '',
            'f_young_file' => '',
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
     * Configure the original E3.5 declaration being changed.
     */
    public function forDeclaration(string $protocol, string $date): static
    {
        return $this->state([
            'f_rel_protocol' => $protocol,
            'f_rel_date' => $date,
        ]);
    }

    /**
     * Configure the same primary schedule for the given days (1=Monday, 7=Sunday).
     *
     * @param array<int, int> $days
     */
    public function withSchedule(string $from, string $to, array $days = [1, 2, 3, 4, 5]): static
    {
        $state = [];
        for ($day = 1; $day <= 7; $day++) {
            $works = in_array($day, $days, true);
            $state["f_time_from_{$day}"] = $works ? $from : '';
            $state["f_time_to_{$day}"] = $works ? $to : '';
        }

        return $this->state($state);
    }
}
