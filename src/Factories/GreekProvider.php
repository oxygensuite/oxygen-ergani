<?php

namespace OxygenSuite\OxygenErgani\Factories;

use DateTimeInterface;
use Faker\Provider\Base;

/**
 * Greek-specific Faker provider for ERGANI data.
 *
 * Generates valid Greek identifiers with proper checksums/formats.
 */
class GreekProvider extends Base
{
    /**
     * Greek first names (male).
     *
     * @var array<int, string>
     */
    protected static array $firstNameMale = [
        'ΓΕΩΡΓΙΟΣ', 'ΙΩΑΝΝΗΣ', 'ΚΩΝΣΤΑΝΤΙΝΟΣ', 'ΔΗΜΗΤΡΙΟΣ', 'ΝΙΚΟΛΑΟΣ',
        'ΠΑΝΑΓΙΩΤΗΣ', 'ΒΑΣΙΛΕΙΟΣ', 'ΧΡΗΣΤΟΣ', 'ΑΘΑΝΑΣΙΟΣ', 'ΜΙΧΑΗΛ',
        'ΕΥΑΓΓΕΛΟΣ', 'ΣΠΥΡΙΔΩΝ', 'ΑΝΤΩΝΙΟΣ', 'ΑΝΑΣΤΑΣΙΟΣ', 'ΕΛΕΥΘΕΡΙΟΣ',
        'ΣΤΥΛΙΑΝΟΣ', 'ΘΕΟΔΩΡΟΣ', 'ΑΛΕΞΑΝΔΡΟΣ', 'ΕΜΜΑΝΟΥΗΛ', 'ΗΛΙΑΣ',
    ];

    /**
     * Greek first names (female).
     *
     * @var array<int, string>
     */
    protected static array $firstNameFemale = [
        'ΜΑΡΙΑ', 'ΕΛΕΝΗ', 'ΑΙΚΑΤΕΡΙΝΗ', 'ΒΑΣΙΛΙΚΗ', 'ΣΟΦΙΑ',
        'ΑΝΑΣΤΑΣΙΑ', 'ΕΥΑΓΓΕΛΙΑ', 'ΓΕΩΡΓΙΑ', 'ΔΗΜΗΤΡΑ', 'ΠΑΡΑΣΚΕΥΗ',
        'ΚΩΝΣΤΑΝΤΙΝΑ', 'ΙΩΑΝΝΑ', 'ΑΘΗΝΑ', 'ΠΑΝΑΓΙΩΤΑ', 'ΧΡΙΣΤΙΝΑ',
        'ΑΓΓΕΛΙΚΗ', 'ΣΤΑΥΡΟΥΛΑ', 'ΘΕΟΔΩΡΑ', 'ΟΛΓΑ', 'ΦΩΤΕΙΝΗ',
    ];

    /**
     * Greek last names.
     *
     * @var array<int, string>
     */
    protected static array $lastName = [
        'ΠΑΠΑΔΟΠΟΥΛΟΣ', 'ΒΑΣΙΛΕΙΟΥ', 'ΑΝΤΩΝΙΟΥ', 'ΓΕΩΡΓΙΟΥ', 'ΝΙΚΟΛΑΟΥ',
        'ΠΑΠΑΓΕΩΡΓΙΟΥ', 'ΙΩΑΝΝΟΥ', 'ΚΩΝΣΤΑΝΤΙΝΟΥ', 'ΔΗΜΗΤΡΙΟΥ', 'ΠΑΝΑΓΙΩΤΟΥ',
        'ΑΘΑΝΑΣΙΟΥ', 'ΧΡΙΣΤΟΔΟΥΛΟΥ', 'ΑΛΕΞΑΝΔΡΟΥ', 'ΜΙΧΑΗΛΙΔΗΣ', 'ΚΑΡΑΓΙΑΝΝΗΣ',
        'ΟΙΚΟΝΟΜΟΥ', 'ΠΑΠΠΑΣ', 'ΜΑΚΡΗΣ', 'ΑΠΟΣΤΟΛΟΥ', 'ΣΤΑΜΑΤΙΟΥ',
    ];

    /**
     * Generate a valid Greek AFM (Tax Identification Number).
     *
     * AFM is 9 digits where the last digit is a checksum.
     * Algorithm: Sum of (digit[i] * 2^(8-i)) mod 11, then mod 10
     */
    public function afm(): string
    {
        // Generate first 8 digits
        $digits = [];
        for ($i = 0; $i < 8; $i++) {
            $digits[] = $this->generator->numberBetween(0, 9);
        }

        // Calculate checksum (9th digit)
        $sum = 0;
        for ($i = 0; $i < 8; $i++) {
            $sum += $digits[$i] * (1 << (8 - $i)); // 2^(8-i)
        }
        $checksum = $sum % 11;
        if ($checksum === 10) {
            $checksum = 0;
        }
        $digits[] = $checksum;

        return implode('', $digits);
    }

    /**
     * Generate a valid Greek AMKA (Social Security Number).
     *
     * AMKA is 11 digits: DDMMYY + 5 random digits
     * Format: birthdate (6 digits) + sequence (5 digits)
     */
    public function amka(?DateTimeInterface $birthDate = null): string
    {
        if ($birthDate === null) {
            $birthDate = $this->generator->dateTimeBetween('-65 years', '-18 years');
        }

        // First 6 digits are birth date in DDMMYY format
        $datePart = $birthDate->format('dmy');

        // Last 5 digits are a random sequence
        $sequence = str_pad((string) $this->generator->numberBetween(0, 99999), 5, '0', STR_PAD_LEFT);

        return $datePart . $sequence;
    }

    /**
     * Generate a Greek ID number (ADT - Δελτίο Αστυνομικής Ταυτότητας).
     *
     * Format: 1-2 Greek letters + 6 digits
     */
    public function greekIdNumber(): string
    {
        $letters = ['Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ'];
        $prefix = $this->generator->randomElement($letters);

        // Sometimes add a second letter
        if ($this->generator->boolean(30)) {
            $prefix .= $this->generator->randomElement($letters);
        }

        $number = str_pad((string) $this->generator->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT);

        return $prefix . $number;
    }

    /**
     * Generate a Greek first name.
     */
    public function greekFirstName(?string $gender = null): string
    {
        if ($gender === null) {
            $gender = $this->generator->randomElement(['male', 'female']);
        }

        return $gender === 'male'
            ? $this->generator->randomElement(self::$firstNameMale)
            : $this->generator->randomElement(self::$firstNameFemale);
    }

    /**
     * Generate a Greek last name.
     */
    public function greekLastName(): string
    {
        return $this->generator->randomElement(self::$lastName);
    }

    /**
     * Generate AMIKA (IKA Insurance Number).
     *
     * Format: 8 digits
     */
    public function amika(): string
    {
        return str_pad((string) $this->generator->numberBetween(10000000, 99999999), 8, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a date string in Greek format (DD/MM/YYYY).
     */
    public function greekDate(string $startDate = '-65 years', string $endDate = '-18 years'): string
    {
        return $this->generator->dateTimeBetween($startDate, $endDate)->format('d/m/Y');
    }

    /**
     * Generate a time string in HH:MM format.
     */
    public function time24h(): string
    {
        $hour = str_pad((string) $this->generator->numberBetween(6, 22), 2, '0', STR_PAD_LEFT);
        $minute = $this->generator->randomElement(['00', '15', '30', '45']);

        return $hour . ':' . $minute;
    }

    /**
     * Generate a work end time based on start time (8-10 hours later).
     */
    public function workEndTime(string $startTime, int $hoursWorked = 8): string
    {
        [$hour, $minute] = explode(':', $startTime);
        $endHour = ((int) $hour + $hoursWorked) % 24;

        return str_pad((string) $endHour, 2, '0', STR_PAD_LEFT) . ':' . $minute;
    }
}
