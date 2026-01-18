<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Dismissal;

use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\EndOfLoan;
use OxygenSuite\OxygenErgani\Models\Dismissal\EndOfLoanDeclaration;
use Tests\TestCase;

class EndOfLoanTest extends TestCase
{
    public function test_submit(): void
    {
        $declaration = EndOfLoanDeclaration::factory()
            ->genuineLoan()
            ->loanPeriod('01/07/2025', '17/01/2026')
            ->fromCompany('123456789', 'ACME CORP')
            ->make([
                'f_afm' => '999999999',
                'f_comments' => 'End of employee loan',
            ]);

        $document = new EndOfLoan('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'end-of-loan.json'));
        $response = $document->handle($declaration);

        $this->assertIsArray($response);
        $this->assertSame('603', $response[0]->id);
        $this->assertSame('E6LD603', $response[0]->protocol);
        $this->assertSame('17/01/2026 10:45', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_submit_with_multiple_declarations(): void
    {
        $declaration1 = EndOfLoanDeclaration::factory()->make();
        $declaration2 = EndOfLoanDeclaration::factory()->make([
            'f_afm' => '888888888',
            'f_eponymo' => 'ALLOS',
            'f_onoma' => 'KAPOIOS',
        ]);

        $document = new EndOfLoan('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'end-of-loan.json'));
        $response = $document->handle([$declaration1, $declaration2]);

        $this->assertIsArray($response);
        $this->assertSame('603', $response[0]->id);
    }

    public function test_model_loan_type(): void
    {
        $declaration = EndOfLoanDeclaration::make()
            ->setLoanType(LoanType::GENUINE);

        $this->assertSame(0, $declaration->getLoanType());

        $declaration->setLoanType(LoanType::EPA);
        $this->assertSame(1, $declaration->getLoanType());
    }

    public function test_model_loan_dates(): void
    {
        $declaration = EndOfLoanDeclaration::make()
            ->setLoanStartDate('01/07/2025')
            ->setLoanEndDate('17/01/2026');

        $this->assertSame('01/07/2025', $declaration->getLoanStartDate());
        $this->assertSame('17/01/2026', $declaration->getLoanEndDate());
    }

    public function test_model_borrowing_company(): void
    {
        $declaration = EndOfLoanDeclaration::make()
            ->setBorrowingCompanyAfm('123456789')
            ->setBorrowingCompanyName('ACME CORPORATION');

        $this->assertSame('123456789', $declaration->getBorrowingCompanyAfm());
        $this->assertSame('ACME CORPORATION', $declaration->getBorrowingCompanyName());
    }

    public function test_model_to_sorted_array(): void
    {
        $declaration = EndOfLoanDeclaration::factory()->make();

        $array = $declaration->toSortedArray();

        // Branch/Location
        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_ypiresia_sepe', $array);

        // Personal
        $this->assertArrayHasKey('f_eponymo', $array);
        $this->assertArrayHasKey('f_onoma', $array);
        $this->assertArrayHasKey('f_afm', $array);

        // Loan Details
        $this->assertArrayHasKey('f_borrow_type', $array);
        $this->assertArrayHasKey('f_borrow_date_from', $array);
        $this->assertArrayHasKey('f_borrow_date_to', $array);
        $this->assertArrayHasKey('f_borrow_company_afm', $array);
        $this->assertArrayHasKey('f_borrow_company_eponimia', $array);

        // Should NOT have salary/compensation fields
        $this->assertArrayNotHasKey('f_apodoxes', $array);
        $this->assertArrayNotHasKey('f_posoapozimiosis', $array);
        $this->assertArrayNotHasKey('f_file', $array);
    }

    public function test_factory_loan_type_states(): void
    {
        $genuine = EndOfLoanDeclaration::factory()->genuineLoan()->make();
        $this->assertSame('0', $genuine->get('f_borrow_type'));

        $epa = EndOfLoanDeclaration::factory()->epaLoan()->make();
        $this->assertSame('1', $epa->get('f_borrow_type'));
    }

    public function test_factory_company_state(): void
    {
        $declaration = EndOfLoanDeclaration::factory()
            ->fromCompany('987654321', 'TEST COMPANY')
            ->make();

        $this->assertSame('987654321', $declaration->get('f_borrow_company_afm'));
        $this->assertSame('TEST COMPANY', $declaration->get('f_borrow_company_eponimia'));
    }

    public function test_factory_loan_period_state(): void
    {
        $declaration = EndOfLoanDeclaration::factory()
            ->loanPeriod('01/01/2025', '31/12/2025')
            ->make();

        $this->assertSame('01/01/2025', $declaration->get('f_borrow_date_from'));
        $this->assertSame('31/12/2025', $declaration->get('f_borrow_date_to'));
    }
}
