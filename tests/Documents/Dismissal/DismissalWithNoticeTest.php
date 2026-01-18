<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\NoticePeriodMonths;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithNotice;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithNoticeDeclaration;
use Tests\TestCase;

class DismissalWithNoticeTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = DismissalWithNoticeDeclaration::factory()
            ->withSalary(1500.00)
            ->withSeverance(3000.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'Dismissal with notice',
            ]);

        $document = new DismissalWithNotice('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'dismissal-with-notice.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('601', $response[0]->id);
        $this->assertSame('E6NMP601', $response[0]->protocol);
        $this->assertSame('17/01/2026 10:35', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = DismissalWithNoticeDeclaration::factory()->make();
        $declaration2 = DismissalWithNoticeDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new DismissalWithNotice('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'dismissal-with-notice.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('601', $response[0]->id);
    }

    public function test_model_employment_classification(): void
    {
        $declaration = DismissalWithNoticeDeclaration::make()
            ->setEmploymentStatus(EmploymentStatus::PARTIAL)
            ->setWorkerType(WorkerType::WORKER)
            ->setSpecialtyCode('313200');

        $this->assertSame('1', $declaration->getEmploymentStatus());
        $this->assertSame('0', $declaration->getWorkerType());
        $this->assertSame('313200', $declaration->getSpecialtyCode());
    }

    public function test_model_notice_period(): void
    {
        $declaration = DismissalWithNoticeDeclaration::make()
            ->setNoticeDate('01/01/2026')
            ->setNoticePeriodMonths(NoticePeriodMonths::TWO);

        $this->assertSame('01/01/2026', $declaration->getNoticeDate());
        $this->assertSame(2, $declaration->getNoticePeriodMonths());
    }

    public function test_model_collective_dismissal(): void
    {
        $declaration = DismissalWithNoticeDeclaration::make()
            ->setCollectiveDismissal(true)
            ->setCollectiveDismissalNumber('ΑΠ-654321')
            ->setCollectiveDismissalDate('05/01/2026');

        $this->assertTrue($declaration->isCollectiveDismissal());
        $this->assertSame('ΑΠ-654321', $declaration->getCollectiveDismissalNumber());
        $this->assertSame('05/01/2026', $declaration->getCollectiveDismissalDate());
    }

    public function test_model_salary_and_compensation(): void
    {
        $declaration = DismissalWithNoticeDeclaration::make()
            ->setGrossSalary(2000.00)
            ->setCompensationAmount(4000.00);

        $this->assertSame(2000.0, $declaration->getGrossSalary());
        $this->assertSame(4000.0, $declaration->getCompensationAmount());
    }

    public function test_model_dates(): void
    {
        $declaration = DismissalWithNoticeDeclaration::make()
            ->setHiringDate('01/06/2020')
            ->setDismissalDate('17/02/2026');

        $this->assertSame('01/06/2020', $declaration->getHiringDate());
        $this->assertSame('17/02/2026', $declaration->getDismissalDate());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = DismissalWithNoticeDeclaration::factory()
            ->withSalary(1500.00)
            ->withSeverance(3000.00)
            ->make();

        $array = $declaration->toSortedArray();

        // Branch/Location
        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);

        // Employment Classification
        $this->assertArrayHasKey('f_kathestosapasxolisis', $array);
        $this->assertArrayHasKey('f_xaraktirismos', $array);
        $this->assertArrayHasKey('f_eidikothta', $array);

        // Notice Period
        $this->assertArrayHasKey('f_proidopoihshdate', $array);
        $this->assertArrayHasKey('f_minesproidopoihsh', $array);

        // Collective Dismissal
        $this->assertArrayHasKey('f_omadiki', $array);

        // Employment Dates
        $this->assertArrayHasKey('f_proslipsidate', $array);
        $this->assertArrayHasKey('f_apolysisdate', $array);

        // Salary and Compensation
        $this->assertArrayHasKey('f_apodoxes', $array);
        $this->assertArrayHasKey('f_posoapozimiosis', $array);

        // Files
        $this->assertArrayHasKey('f_file', $array);
    }

    public function test_greek_float_casting(): void
    {
        $declaration = DismissalWithNoticeDeclaration::factory()
            ->withSalary(2500.75)
            ->withSeverance(5000.25)
            ->make();

        $array = $declaration->toSortedArray();

        $this->assertSame('2.500,75', $array['f_apodoxes']);
        $this->assertSame('5.000,25', $array['f_posoapozimiosis']);
    }

    public function test_factory_notice_period_state(): void
    {
        $declaration = DismissalWithNoticeDeclaration::factory()
            ->noticePeriod(NoticePeriodMonths::THREE, '01/11/2025')
            ->make();

        $this->assertSame('3', $declaration->get('f_minesproidopoihsh'));
        $this->assertSame('01/11/2025', $declaration->get('f_proidopoihshdate'));
    }
}
