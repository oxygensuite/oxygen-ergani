<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\PreAnnouncement;

use OxygenSuite\OxygenErgani\Http\Documents\PreAnnouncement\PreAnnouncementExemption;
use OxygenSuite\OxygenErgani\Models\PreAnnouncement\ExemptionDeclaration;
use Tests\TestCase;

class PreAnnouncementExemptionTest extends TestCase
{
    public function test_pre_announcement_exemption_submit(): void
    {
        $declaration = ExemptionDeclaration::make()
            ->setBranchCode(0)
            ->setIsExcluded(true)
            ->setMonth(2)
            ->setYear(2026)
            ->setComments('test-comments');

        $document = new PreAnnouncementExemption('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'pre-announcement-exemption.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('201', $response[0]->id);
        $this->assertSame('EP201', $response[0]->protocol);
        $this->assertSame('19/02/2026 12:00', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_pre_announcement_exemption_model(): void
    {
        $declaration = ExemptionDeclaration::make()
            ->setBranchCode(0)
            ->setIsExcluded(false)
            ->setMonth(3)
            ->setYear(2026)
            ->setComments('Test comments');

        $this->assertSame(0, $declaration->getBranchCode());
        $this->assertSame('0', $declaration->getIsExcluded());
        $this->assertSame('03', $declaration->getMonth());
        $this->assertSame('2026', $declaration->getYear());
        $this->assertSame('Test comments', $declaration->getComments());
    }

    public function test_pre_announcement_exemption_to_sorted_array(): void
    {
        $declaration = ExemptionDeclaration::make()
            ->setBranchCode(0)
            ->setIsExcluded('1')
            ->setMonth('02')
            ->setYear('2026')
            ->setComments('');

        $array = $declaration->toSortedArray();

        $expectedKeys = [
            'f_aa_pararthmatos',
            'f_is_excluded',
            'f_month',
            'f_year',
            'f_comments',
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $array);
        }

        $this->assertSame(array_keys($array), $expectedKeys);
    }

    public function test_pre_announcement_month_padding(): void
    {
        $declaration = ExemptionDeclaration::make()
            ->setMonth(1);

        $this->assertSame('01', $declaration->getMonth());

        $declaration->setMonth(12);
        $this->assertSame('12', $declaration->getMonth());
    }
}
