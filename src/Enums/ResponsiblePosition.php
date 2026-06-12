<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Indicates whether the employee holds a supervisory, managerial, or confidential position.
 *
 * Used in E3 hiring declaration forms (f_responsible_position field).
 */
enum ResponsiblePosition: string
{
    use HasLabels;

    #[Label('Not applicable', 'Δεν εφαρμόζεται')]
    case NONE = '';

    #[Label('No', 'Όχι')]
    case NO = '1';

    #[Label('Position with managerial authority', 'Θέση με διευθυντικό δικαίωμα')]
    case MANAGERIAL_AUTHORITY = '2';

    #[Label('Salary at least 4x minimum wage', 'Αποδοχές τουλάχιστον 4πλάσιες του κατώτατου μισθού')]
    case SALARY_4X_MINIMUM = '3';

    #[Label('Salary at least 6x minimum wage', 'Αποδοχές τουλάχιστον 6πλάσιες του κατώτατου μισθού')]
    case SALARY_6X_MINIMUM = '4';
}
