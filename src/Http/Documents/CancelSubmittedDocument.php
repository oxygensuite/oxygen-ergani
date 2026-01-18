<?php

namespace OxygenSuite\OxygenErgani\Http\Documents;

use DateTime;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Client;

class CancelSubmittedDocument extends Client
{
    private const URI = 'Documents/CancelSubmittedDocument';

    /**
     * To Cancel Document είναι υπεύθυνο για την ανάκληση υποβληθείσας δήλωσης
     * υποβολής, για όσες διαδικασίες προβλέπεται. (Αυτή τη στιγμή προβλέπεται για τις
     * διαδικασίες:
     * - Οργάνωση Χρόνου Εργασίας – Άδειες
     * - Οργάνωση Χρόνου Εργασίας – Άδειες ΟΡΘΗ ΕΠΑΝΑΛΗΨΗ
     *
     * @param string              $documentType   Ο τύπος της δήλωσης (κωδικός ενεργής υποβολής)
     * @param string              $protocol       Ο αριθμός πρωτοκόλλου της δήλωσης
     * @param DateTime|int|string $submissionDate Η ημερομηνία υποβολής της δήλωσης yyyymmdd
     *
     * @return bool            Επιστρέφει true αν η ανάκληση ήταν επιτυχής
     * @throws ErganiException
     */
    public function handle(string $documentType, string $protocol, DateTime|int|string $submissionDate): bool
    {
        return $this->post(self::URI, [
            'TypeOfDocument' => $documentType,
            'Protocol' => $protocol,
            'SubmittedDate' => $submissionDate instanceof DateTime ? $submissionDate->format('Ymd') : $submissionDate,
        ])->isSuccessful();
    }
}
