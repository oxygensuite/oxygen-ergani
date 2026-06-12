<?php

namespace OxygenSuite\OxygenErgani\Enums;

use OxygenSuite\OxygenErgani\Attributes\Label;
use OxygenSuite\OxygenErgani\Enums\Concerns\HasLabels;

/**
 * Termination reasons for fixed-term contracts (E7N f_logosperatosis field).
 *
 * Note: Values are non-sequential (0, 3, 4, 5, 6) as defined in the XSD.
 */
enum FixedTermTerminationReason: int
{
    use HasLabels;

    #[Label('Contract Expiration', 'Λήξη Συμπεφωνημένου Χρόνου')]
    case CONTRACT_EXPIRATION = 0;

    #[Label('Work Completion', 'Ολοκλήρωση Έργου με Όρο Πρόωρης Καταγγελίας')]
    case WORK_COMPLETION = 3;

    #[Label('Early Termination by Employer', 'Καταγγελία πριν Λήξη για Σπουδαίο Λόγο')]
    case EARLY_BY_EMPLOYER = 4;

    #[Label('Early Termination by Employee', 'Καταγγελία πριν Λήξη χωρίς Σπουδαίο Λόγο')]
    case EARLY_BY_EMPLOYEE = 5;

    #[Label('Mutual Agreement', 'Συναινετική Λύση πριν Λήξη')]
    case MUTUAL_AGREEMENT = 6;
}
