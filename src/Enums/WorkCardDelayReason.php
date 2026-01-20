<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Reasons for delayed work card submissions.
 *
 * Used when submitting work cards after the allowed time window
 * to explain the technical reason for the delay.
 */
enum WorkCardDelayReason: string
{
    use HasLabels;

    #[Label('Power or telecom issue', 'Πρόβλημα στην ηλεκτροδότηση/τηλεπικοινωνίες')]
    case POWER_OR_TELECOM_ISSUE = '001';

    #[Label('Employer system issue', 'Πρόβλημα στα συστήματα του εργοδότη')]
    case EMPLOYER_SYSTEM_ISSUE = '002';

    #[Label('ERGANI connection issue', 'Πρόβλημα σύνδεσης με το ΠΣ ΕΡΓΑΝΗ')]
    case ERGANI_CONNECTION_ISSUE = '003';
}
