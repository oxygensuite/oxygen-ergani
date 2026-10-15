<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Internship;

use DateTime;
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipScheduleChange;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipScheduleChangeDeclaration;
use Tests\TestCase;

class InternshipScheduleChangeTest extends TestCase
{
    public function test_internship_schedule_change_submit(): void
    {
        $document = new InternshipScheduleChange('test-access-token');
        $mock = $this->mockResponse(200, 'internship-schedule-change.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle([$this->sampleDeclaration()]);

        $this->assertStringEndsWith('Documents/WebE35T', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('420', $response[0]->id);
        $this->assertSame('E35T-420', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['PraktikesE35T']['PraktikhE35T']);
    }

    /**
     * The payload must match the official ERGANI E3.5 schedule change sample (testE35T.json),
     * field for field and in order.
     */
    public function test_matches_ergani_sample(): void
    {
        $expected = json_decode($this->readFile('internship-sample-e35t.json'), true);

        $this->assertSame($expected, $this->sampleDeclaration()->toSortedArray());
    }

    public function test_model_accessors(): void
    {
        $declaration = $this->sampleDeclaration()
            ->setSchedule(6, '10:00', '14:00')
            ->setSplitSchedule(6, '', '')
            ->setBreakSchedule(6, '12:00', '12:15');

        $this->assertSame('0', $declaration->getBranchCode());
        $this->assertSame('ΠΡ1', $declaration->getRelatedProtocol());
        $this->assertSame('10/04/2024', $declaration->getRelatedDate());
        $this->assertSame('000000000', $declaration->getAfm());
        $this->assertSame('13/09/2024', $declaration->getChangeDate());
        $this->assertSame(['from' => '10:00', 'to' => '14:00'], $declaration->getSchedule(6));
        $this->assertSame(['from' => '', 'to' => ''], $declaration->getSplitSchedule(6));
        $this->assertSame(['from' => '12:00', 'to' => '12:15'], $declaration->getBreakSchedule(6));
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $declaration->getCertifierLastName());
        $this->assertSame('ΓΕΩΡΓΙΟΣ', $declaration->getCertifierFirstName());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getCertifierCapacity());
        $this->assertSame('ΔΟΚΙΜΗ', $declaration->getCertifierAddress());
        $this->assertSame('000000001', $declaration->getCertifierAfm());
        $this->assertSame('078776545', $declaration->getLegalRepresentativeAfm());
    }

    public function test_with_defaults_fills_unset_time_fields(): void
    {
        $array = InternshipScheduleChangeDeclaration::make()
            ->setAfm('000000000')
            ->setSchedule(1, '09:00', '17:00')
            ->withDefaults()
            ->toSortedArray();

        $this->assertCount(56, $array);
        $this->assertSame('09:00', $array['f_time_from_1']);
        $this->assertSame('', $array['f_time_from_7']);
        $this->assertSame('', $array['f_break_time_to_7']);
    }

    public function test_datetime_support(): void
    {
        $declaration = InternshipScheduleChangeDeclaration::make()
            ->setRelatedDate(new DateTime('2024-04-10'))
            ->setChangeDate(new DateTime('2024-09-13'));

        $this->assertSame('10/04/2024', $declaration->getRelatedDate());
        $this->assertSame('13/09/2024', $declaration->getChangeDate());
    }

    private function sampleDeclaration(): InternshipScheduleChangeDeclaration
    {
        $declaration = InternshipScheduleChangeDeclaration::make()
            ->setBranchCode('0')
            ->setRelatedProtocol('ΠΡ1')
            ->setRelatedDate('10/04/2024')
            ->setAfm('000000000')
            ->setChangeDate('13/09/2024');

        for ($day = 1; $day <= 7; $day++) {
            $declaration
                ->setSchedule($day, '08:00', '08:00')
                ->setSplitSchedule($day, '08:00', '08:00')
                ->setBreakSchedule($day, '08:00', '08:00');
        }

        return $declaration
            ->setCertifierLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setCertifierFirstName('ΓΕΩΡΓΙΟΣ')
            ->setCertifierCapacity('ΔΟΚΙΜΗ')
            ->setCertifierAddress('ΔΟΚΙΜΗ')
            ->setCertifierAfm('000000001')
            ->setLegalRepresentativeAfm('078776545')
            ->setFormFile('')
            ->setForeignFile('')
            ->setMinorFile('');
    }
}
