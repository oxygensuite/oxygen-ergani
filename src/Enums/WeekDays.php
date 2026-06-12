<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Number of working days per week.
 *
 * Used in E3 hiring declaration forms (f_week_days field).
 */
enum WeekDays: int
{
    use HasLabels;

    #[Label('Five-day week', 'Πενθήμερο')]
    case FIVE_DAY = 5;

    #[Label('Six-day week', 'Εξαήμερο')]
    case SIX_DAY = 6;
}
