<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Employee's biological sex.
 *
 * Used in E3 hiring declaration forms (f_sex field).
 */
enum Sex: int
{
    use HasLabels;

    #[Label('Male', 'Άνδρας')]
    case MALE = 0;

    #[Label('Female', 'Γυναίκα')]
    case FEMALE = 1;
}
