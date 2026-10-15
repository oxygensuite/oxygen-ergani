<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Who owns, possesses or holds the vehicle used for work.
 *
 * Used in E13 motorcycle use declarations (f_is_owner field).
 */
enum VehicleOwner: string
{
    use HasLabels;

    #[Label('Employer', 'Εργοδότης')]
    case EMPLOYER = '0';

    #[Label('Employee', 'Εργαζόμενος')]
    case EMPLOYEE = '1';
}
