<?php

namespace Tests\Factories;

use DateTime;
use OxygenSuite\OxygenErgani\Factories\Factory;
use PHPUnit\Framework\TestCase;

class GreekProviderTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testAfmGeneratesValidNineDigitNumber(): void
    {
        $afm = Factory::fake()->afm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testAfmHasValidChecksum(): void
    {
        $afm = Factory::fake()->afm();
        $digits = str_split($afm);

        // Calculate checksum
        $sum = 0;
        for ($i = 0; $i < 8; $i++) {
            $sum += (int) $digits[$i] * (1 << (8 - $i));
        }
        $expectedChecksum = $sum % 11;
        if ($expectedChecksum === 10) {
            $expectedChecksum = 0;
        }

        $this->assertEquals($expectedChecksum, (int) $digits[8]);
    }

    public function testAmkaGeneratesElevenDigitNumber(): void
    {
        $amka = Factory::fake()->amka();

        $this->assertMatchesRegularExpression('/^\d{11}$/', $amka);
    }

    public function testAmkaIncludesBirthDate(): void
    {
        $birthDate = new DateTime('1990-05-15');
        $amka = Factory::fake()->amka($birthDate);

        // First 6 digits should be DDMMYY = 150590
        $this->assertStringStartsWith('150590', $amka);
    }

    public function testGreekIdNumberMatchesExpectedFormat(): void
    {
        $idNumber = Factory::fake()->greekIdNumber();

        // 1-2 Greek letters followed by 6 digits
        // Letters from the provider: Α Β Γ Δ Ε Ζ Η Θ Ι Κ Λ Μ Ν Ξ Ο Π Ρ Σ Τ
        $this->assertMatchesRegularExpression('/^[\x{0391}-\x{03A9}]{1,2}\d{6}$/u', $idNumber);
    }

    public function testGreekFirstNameMale(): void
    {
        $name = Factory::fake()->greekFirstName('male');

        $maleNames = [
            'ΓΕΩΡΓΙΟΣ', 'ΙΩΑΝΝΗΣ', 'ΚΩΝΣΤΑΝΤΙΝΟΣ', 'ΔΗΜΗΤΡΙΟΣ', 'ΝΙΚΟΛΑΟΣ',
            'ΠΑΝΑΓΙΩΤΗΣ', 'ΒΑΣΙΛΕΙΟΣ', 'ΧΡΗΣΤΟΣ', 'ΑΘΑΝΑΣΙΟΣ', 'ΜΙΧΑΗΛ',
            'ΕΥΑΓΓΕΛΟΣ', 'ΣΠΥΡΙΔΩΝ', 'ΑΝΤΩΝΙΟΣ', 'ΑΝΑΣΤΑΣΙΟΣ', 'ΕΛΕΥΘΕΡΙΟΣ',
            'ΣΤΥΛΙΑΝΟΣ', 'ΘΕΟΔΩΡΟΣ', 'ΑΛΕΞΑΝΔΡΟΣ', 'ΕΜΜΑΝΟΥΗΛ', 'ΗΛΙΑΣ',
        ];

        $this->assertContains($name, $maleNames);
    }

    public function testGreekFirstNameFemale(): void
    {
        $name = Factory::fake()->greekFirstName('female');

        $femaleNames = [
            'ΜΑΡΙΑ', 'ΕΛΕΝΗ', 'ΑΙΚΑΤΕΡΙΝΗ', 'ΒΑΣΙΛΙΚΗ', 'ΣΟΦΙΑ',
            'ΑΝΑΣΤΑΣΙΑ', 'ΕΥΑΓΓΕΛΙΑ', 'ΓΕΩΡΓΙΑ', 'ΔΗΜΗΤΡΑ', 'ΠΑΡΑΣΚΕΥΗ',
            'ΚΩΝΣΤΑΝΤΙΝΑ', 'ΙΩΑΝΝΑ', 'ΑΘΗΝΑ', 'ΠΑΝΑΓΙΩΤΑ', 'ΧΡΙΣΤΙΝΑ',
            'ΑΓΓΕΛΙΚΗ', 'ΣΤΑΥΡΟΥΛΑ', 'ΘΕΟΔΩΡΑ', 'ΟΛΓΑ', 'ΦΩΤΕΙΝΗ',
        ];

        $this->assertContains($name, $femaleNames);
    }

    public function testGreekLastName(): void
    {
        $lastName = Factory::fake()->greekLastName();

        $lastNames = [
            'ΠΑΠΑΔΟΠΟΥΛΟΣ', 'ΒΑΣΙΛΕΙΟΥ', 'ΑΝΤΩΝΙΟΥ', 'ΓΕΩΡΓΙΟΥ', 'ΝΙΚΟΛΑΟΥ',
            'ΠΑΠΑΓΕΩΡΓΙΟΥ', 'ΙΩΑΝΝΟΥ', 'ΚΩΝΣΤΑΝΤΙΝΟΥ', 'ΔΗΜΗΤΡΙΟΥ', 'ΠΑΝΑΓΙΩΤΟΥ',
            'ΑΘΑΝΑΣΙΟΥ', 'ΧΡΙΣΤΟΔΟΥΛΟΥ', 'ΑΛΕΞΑΝΔΡΟΥ', 'ΜΙΧΑΗΛΙΔΗΣ', 'ΚΑΡΑΓΙΑΝΝΗΣ',
            'ΟΙΚΟΝΟΜΟΥ', 'ΠΑΠΠΑΣ', 'ΜΑΚΡΗΣ', 'ΑΠΟΣΤΟΛΟΥ', 'ΣΤΑΜΑΤΙΟΥ',
        ];

        $this->assertContains($lastName, $lastNames);
    }

    public function testAmikaGeneratesEightDigitNumber(): void
    {
        $amika = Factory::fake()->amika();

        $this->assertMatchesRegularExpression('/^\d{8}$/', $amika);
        $this->assertGreaterThanOrEqual(10000000, (int) $amika);
    }

    public function testGreekDateFormat(): void
    {
        $date = Factory::fake()->greekDate();

        $this->assertMatchesRegularExpression('/^\d{2}\/\d{2}\/\d{4}$/', $date);
    }

    public function testTime24hFormat(): void
    {
        $time = Factory::fake()->time24h();

        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $time);

        [$hour, $minute] = explode(':', $time);
        $this->assertGreaterThanOrEqual(6, (int) $hour);
        $this->assertLessThanOrEqual(22, (int) $hour);
        $this->assertContains($minute, ['00', '15', '30', '45']);
    }

    public function testWorkEndTimeCalculatesCorrectly(): void
    {
        $startTime = '08:00';
        $endTime = Factory::fake()->workEndTime($startTime, 8);

        $this->assertEquals('16:00', $endTime);
    }

    public function testWorkEndTimeHandlesMidnight(): void
    {
        $startTime = '20:00';
        $endTime = Factory::fake()->workEndTime($startTime, 8);

        $this->assertEquals('04:00', $endTime);
    }
}
