<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * How a parental leave is granted.
 *
 * Used in E14 parental leave declarations (f_tropos_xorhghshs_adeias field).
 */
enum LeaveGrantMethod: string
{
    use HasLabels;

    #[Label('Continuous', 'Συνεχόμενα')]
    case CONTINUOUS = '0';

    #[Label('Partial, flexible', 'Τμηματικά με ευέλικτο τρόπο')]
    case FLEXIBLE = '1';
}
