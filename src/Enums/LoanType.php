<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Type of employee loan arrangement.
 *
 * Used in E6LD end-of-loan declaration forms (f_borrow_type field).
 */
enum LoanType: int
{
    use HasLabels;

    #[Label('Genuine Loan', 'Γνήσιος Δανεισμός')]
    case GENUINE = 0;

    #[Label('EPA (Temporary Employment Agency)', 'ΕΠΑ')]
    case EPA = 1;
}
