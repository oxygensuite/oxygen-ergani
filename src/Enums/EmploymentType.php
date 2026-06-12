<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Type of employment contract duration.
 *
 * Used in E3 hiring, E5 termination, and MA employment change declaration forms (f_sxeshapasxolisis field).
 */
enum EmploymentType: int
{
    use HasLabels;

    #[Label('Indefinite term', 'Αορίστου χρόνου')]
    case INDEFINITE = 0;

    #[Label('Fixed term', 'Ορισμένου χρόνου')]
    case FIXED_TERM = 1;

    #[Label('Project-based', 'Έργου')]
    case PROJECT = 2;

    #[Label('Borrowed employee', 'Δανειζόμενος')]
    case BORROWED = 3;
}
