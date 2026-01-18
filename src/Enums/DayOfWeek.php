<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Day of the week.
 *
 * Used in work time organization schedules.
 */
enum DayOfWeek: string
{
    use HasLabels;

    #[Label('Monday', 'Δευτέρα')]
    case MONDAY = '1';

    #[Label('Tuesday', 'Τρίτη')]
    case TUESDAY = '2';

    #[Label('Wednesday', 'Τετάρτη')]
    case WEDNESDAY = '3';

    #[Label('Thursday', 'Πέμπτη')]
    case THURSDAY = '4';

    #[Label('Friday', 'Παρασκευή')]
    case FRIDAY = '5';

    #[Label('Saturday', 'Σάββατο')]
    case SATURDAY = '6';

    #[Label('Sunday', 'Κυριακή')]
    case SUNDAY = '7';
}
