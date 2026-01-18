<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Type of work time entry for daily work time declarations (WTO).
 */
enum WorkTimeType: string
{
    use HasLabels;

    // Work types
    #[Label('Work', 'Εργασία')]
    case WORK = 'ΕΡΓ';

    #[Label('External work', 'Εργασία εκτός έδρας')]
    case WORK_EXTERNAL = 'ΕΡΓ.ΕΞ';

    #[Label('Overtime', 'Υπερωρία')]
    case OVERTIME = 'ΥΠ';

    #[Label('Non-overtime extra hours', 'Υπερεργασία')]
    case NON_OVERTIME = 'ΜΗ.ΥΠ';

    // Break and day off
    #[Label('Break', 'Διάλειμμα')]
    case BREAK = 'ΔΛ';

    #[Label('Day off', 'Ρεπό')]
    case DAY_OFF = 'ΡΕΠΟ';

    // Leave types
    #[Label('Regular leave', 'Κανονική άδεια')]
    case LEAVE_REGULAR = 'ΑΔ.ΚΑΝ';

    #[Label('Blood donation leave', 'Άδεια αιμοδοσίας')]
    case LEAVE_BLOOD_DONATION = 'ΑΔ.ΑΙΜ';

    #[Label('Examination leave', 'Άδεια εξετάσεων')]
    case LEAVE_EXAMINATION = 'ΑΔ.ΕΞ';

    #[Label('Parental leave', 'Γονική άδεια')]
    case LEAVE_PARENTAL = 'ΑΔ.ΑΝ.Π';

    #[Label('Unpaid leave', 'Άδεια άνευ αποδοχών')]
    case LEAVE_UNPAID = 'ΑΔ.ΑΝ';

    #[Label('Maternity leave', 'Άδεια μητρότητας')]
    case LEAVE_MATERNITY = 'ΑΔ.ΜΗΤ';

    #[Label('Paternity leave', 'Άδεια πατρότητας')]
    case LEAVE_PATERNITY = 'ΑΔ.ΠΑΤ';

    #[Label('Sick leave', 'Άδεια ασθενείας')]
    case LEAVE_SICK = 'ΑΔ.ΑΣΘ';

    #[Label('Special leave', 'Ειδική άδεια')]
    case LEAVE_SPECIAL = 'ΑΔ.ΕΙΔ';

    // Other
    #[Label('Holiday', 'Αργία')]
    case HOLIDAY = 'ΑΡΓ';

    #[Label('Absence', 'Απουσία')]
    case ABSENCE = 'ΑΠ';

    #[Label('Suspension', 'Αναστολή')]
    case SUSPENSION = 'ΑΝΑΣΤ';
}
