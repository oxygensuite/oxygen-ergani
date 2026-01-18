<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithoutNotice;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithoutNoticeDeclaration;
use Tests\TestCase;

class DismissalWithoutNoticeTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = DismissalWithoutNoticeDeclaration::factory()
            ->withSalary(1500.00)
            ->withSeverance(3000.00)
            ->withFormFile('SGVsbG8sIHdvcmxkIQ==')
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'Dismissal without notice',
            ]);

        $document = new DismissalWithoutNotice('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'dismissal-without-notice.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('600', $response[0]->id);
        $this->assertSame('E6NXP600', $response[0]->protocol);
        $this->assertSame('17/01/2026 10:30', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = DismissalWithoutNoticeDeclaration::factory()->make();
        $declaration2 = DismissalWithoutNoticeDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new DismissalWithoutNotice('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'dismissal-without-notice.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('600', $response[0]->id);
    }

    public function test_model_employment_classification(): void
    {
        $declaration = DismissalWithoutNoticeDeclaration::make()
            ->setEmploymentStatus(EmploymentStatus::FULL)
            ->setWorkerType(WorkerType::EMPLOYEE)
            ->setSpecialtyCode('313200');

        $this->assertSame('0', $declaration->getEmploymentStatus());
        $this->assertSame('1', $declaration->getWorkerType());
        $this->assertSame('313200', $declaration->getSpecialtyCode());
    }

    public function test_model_collective_dismissal(): void
    {
        $declaration = DismissalWithoutNoticeDeclaration::make()
            ->setCollectiveDismissal(true)
            ->setCollectiveDismissalNumber('ΑΠ-123456')
            ->setCollectiveDismissalDate('10/01/2026');

        $this->assertTrue($declaration->isCollectiveDismissal());
        $this->assertSame('ΑΠ-123456', $declaration->getCollectiveDismissalNumber());
        $this->assertSame('10/01/2026', $declaration->getCollectiveDismissalDate());
    }

    public function test_model_termination_notification(): void
    {
        $declaration = DismissalWithoutNoticeDeclaration::make()
            ->setTerminationNotificationDate('17/01/2026');

        $this->assertSame('17/01/2026', $declaration->getTerminationNotificationDate());
    }

    public function test_model_salary_and_compensation(): void
    {
        $declaration = DismissalWithoutNoticeDeclaration::make()
            ->setGrossSalary(1500.00)
            ->setCompensationAmount(3000.00);

        $this->assertSame(1500.0, $declaration->getGrossSalary());
        $this->assertSame(3000.0, $declaration->getCompensationAmount());
    }

    public function test_model_dates(): void
    {
        $declaration = DismissalWithoutNoticeDeclaration::make()
            ->setHiringDate('01/06/2020')
            ->setDismissalDate('17/01/2026');

        $this->assertSame('01/06/2020', $declaration->getHiringDate());
        $this->assertSame('17/01/2026', $declaration->getDismissalDate());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = DismissalWithoutNoticeDeclaration::factory()
            ->withSalary(1500.00)
            ->withSeverance(3000.00)
            ->make();

        $array = $declaration->toSortedArray();

        // Branch/Location
        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);
        $this->assertArrayHasKey('f_ypiresia_oaed', $array);

        // Personal
        $this->assertArrayHasKey('f_eponymo', $array);
        $this->assertArrayHasKey('f_onoma', $array);
        $this->assertArrayHasKey('f_birthdate', $array);

        // Employment Classification
        $this->assertArrayHasKey('f_kathestosapasxolisis', $array);
        $this->assertArrayHasKey('f_xaraktirismos', $array);
        $this->assertArrayHasKey('f_eidikothta', $array);

        // Collective Dismissal
        $this->assertArrayHasKey('f_omadiki', $array);

        // Employment Dates
        $this->assertArrayHasKey('f_proslipsidate', $array);
        $this->assertArrayHasKey('f_apolysisdate', $array);

        // Salary and Compensation
        $this->assertArrayHasKey('f_apodoxes', $array);
        $this->assertArrayHasKey('f_koinopoihshdate', $array);
        $this->assertArrayHasKey('f_posoapozimiosis', $array);

        // Files
        $this->assertArrayHasKey('f_file', $array);
        $this->assertArrayHasKey('f_comments', $array);
    }

    public function test_greek_float_casting(): void
    {
        $declaration = DismissalWithoutNoticeDeclaration::factory()
            ->withSalary(1500.00)
            ->withSeverance(3000.50)
            ->make();

        $array = $declaration->toSortedArray();

        $this->assertSame('1.500,00', $array['f_apodoxes']);
        $this->assertSame('3.000,50', $array['f_posoapozimiosis']);
    }

    public function test_factory_states(): void
    {
        $declaration = DismissalWithoutNoticeDeclaration::factory()
            ->fullTime()
            ->asWorker()
            ->asCollectiveDismissal('ΑΠ-999999', '15/01/2026')
            ->make();

        $this->assertSame('0', $declaration->get('f_kathestosapasxolisis'));
        $this->assertSame('0', $declaration->get('f_xaraktirismos'));
        $this->assertSame('1', $declaration->get('f_omadiki'));
        $this->assertSame('ΑΠ-999999', $declaration->get('f_omadikiarithmos'));
    }
}
