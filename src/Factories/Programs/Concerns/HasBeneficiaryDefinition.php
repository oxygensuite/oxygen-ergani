<?php

namespace OxygenSuite\OxygenErgani\Factories\Programs\Concerns;

/**
 * Shared beneficiary (`f_mea_*`) attributes for the E3.2, E3.3 and E3.4 program factories.
 *
 * Merge it into definition() and override what differs per form, e.g. the age range:
 * `[...$this->beneficiaryDefinition(), 'f_mea_date_birth' => ...]`.
 */
trait HasBeneficiaryDefinition
{
    /**
     * @return array<string, mixed>
     */
    protected function beneficiaryDefinition(): array
    {
        $fake = self::fake();
        $male = $fake->boolean();
        $gender = $male ? 'male' : 'female';

        return [
            'f_mea_eponimo' => $fake->greekLastName(),
            'f_mea_onoma' => $fake->greekFirstName($gender),
            'f_mea_eponimo_patera' => '',
            'f_mea_onoma_patera' => $fake->greekFirstName('male'),
            'f_mea_eponimo_miteras' => '',
            'f_mea_onoma_miteras' => $fake->greekFirstName('female'),
            'f_mea_sex' => $male ? '0' : '1',
            'f_mea_birth_city' => 'ΑΘΗΝΑ',
            'f_mea_date_birth' => $fake->greekDate('-60 years', '-20 years'),
            'f_mea_nationality' => '048',
            'f_mea_typos_taytotitas' => 'ΔΑΤ',
            'f_mea_ar_taytotitas' => $fake->greekIdNumber(),
            'f_mea_ekdousa' => 'Α.Τ. ΑΘΗΝΩΝ',
            'f_mea_date_ekdosis' => $fake->greekDate('-10 years', '-1 year'),
            'f_mea_date_ekdosis_lixi' => '',
            'f_mea_res_permit' => '',
            'f_mea_res_permit_type' => '',
            'f_mea_res_permit_ar' => '',
            'f_mea_res_permit_lixi' => '',
            'f_mea_marital_status' => '0',
            'f_mea_ar_teknon' => '0',
            'f_mea_afm' => $fake->afm(),
            'f_mea_doy' => '1101',
            'f_mea_insurance_agency' => '',
            'f_mea_am_ika' => $fake->amika(),
            'f_mea_amka' => $fake->amka(),
            'f_mea_code_anergias' => (string) $fake->numberBetween(1000000, 9999999),
            'f_mea_ar_vivliou_anilikou' => '',
            'f_mea_dieythinsi' => 'ΣΤΑΔΙΟΥ 10',
            'f_mea_kallikratis' => '',
            'f_mea_tk' => '10564',
            'f_mea_til' => '210' . $fake->numberBetween(1000000, 9999999),
            'f_mea_fax' => '',
            'f_mea_email' => '',
            'f_mea_epipedo_morfosis' => '5',
        ];
    }
}
