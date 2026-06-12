<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\SixthDay;

use OxygenSuite\OxygenErgani\Http\Documents\SixthDay\SixthDay;
use OxygenSuite\OxygenErgani\Models\SixthDay\SixthDayDeclaration;
use Tests\TestCase;

class SixthDayTest extends TestCase
{
    public function test_sixth_day_submit(): void
    {
        $declaration = SixthDayDeclaration::make()
            ->setBranchCode(0)
            ->setContinuousOperation(true)
            ->setMainActivityCode('5610')
            ->setSpecialOccasionDescription('Seasonal demand')
            ->setDateFrom('19/02/2026')
            ->setDateTo('19/02/2026')
            ->setComments('test-comments');

        $document = new SixthDay('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'sixth-day.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('200', $response[0]->id);
        $this->assertSame('SD200', $response[0]->protocol);
        $this->assertSame('19/02/2026 12:00', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_sixth_day_model(): void
    {
        $declaration = SixthDayDeclaration::make()
            ->setBranchCode(0)
            ->setContinuousOperation(false)
            ->setMainActivityCode('5610')
            ->setSpecialOccasionDescription('Test description')
            ->setDateFrom('01/03/2026')
            ->setDateTo('31/03/2026')
            ->setComments('Test comments');

        $this->assertSame(0, $declaration->getBranchCode());
        $this->assertSame('0', $declaration->getContinuousOperation());
        $this->assertSame('5610', $declaration->getMainActivityCode());
        $this->assertSame('Test description', $declaration->getSpecialOccasionDescription());
        $this->assertSame('01/03/2026', $declaration->getDateFrom());
        $this->assertSame('31/03/2026', $declaration->getDateTo());
        $this->assertSame('Test comments', $declaration->getComments());
    }

    public function test_sixth_day_to_sorted_array(): void
    {
        $declaration = SixthDayDeclaration::make()
            ->setBranchCode(0)
            ->setContinuousOperation('1')
            ->setMainActivityCode('5610')
            ->setSpecialOccasionDescription('Description')
            ->setDateFrom('19/02/2026')
            ->setDateTo('19/02/2026')
            ->setComments('');

        $array = $declaration->toSortedArray();

        $expectedKeys = [
            'f_aa_pararthmatos',
            'f_continuous_operation',
            'f_kad_kyria',
            'f_special_occasion_description',
            'f_date_special_from',
            'f_date_special_to',
            'f_comments',
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $array);
        }

        $this->assertSame(array_keys($array), $expectedKeys);
    }

    public function test_sixth_day_datetime_support(): void
    {
        $declaration = SixthDayDeclaration::make()
            ->setDateFrom(new \DateTime('2026-02-19'))
            ->setDateTo(new \DateTime('2026-02-19'));

        $this->assertSame('19/02/2026', $declaration->getDateFrom());
        $this->assertSame('19/02/2026', $declaration->getDateTo());
    }
}
