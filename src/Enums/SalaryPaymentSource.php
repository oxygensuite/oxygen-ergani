<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Source of salary payment for borrowed employees.
 *
 * Used in MAD borrowed employment change declaration forms (f_kataboli_apodoxon field).
 */
enum SalaryPaymentSource: int
{
    use HasLabels;

    #[Label('Direct employer/EPA', 'Άμεσος εργοδότης/ΕΠΑ')]
    case DIRECT_EMPLOYER = 0;

    #[Label('Indirect employer', 'Έμμεσος εργοδότης')]
    case INDIRECT_EMPLOYER = 1;
}
