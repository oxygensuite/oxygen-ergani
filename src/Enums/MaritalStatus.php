<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Employee's marital status.
 *
 * Used in E3 hiring declaration forms (f_marital_status field).
 */
enum MaritalStatus: int
{
    use HasLabels;

    #[Label('Single', 'Άγαμος/η')]
    case SINGLE = 0;

    #[Label('Married', 'Έγγαμος/η')]
    case MARRIED = 1;

    #[Label('Divorced', 'Διαζευγμένος/η')]
    case DIVORCED = 2;

    #[Label('Widowed', 'Χήρος/α')]
    case WIDOWED = 3;
}
