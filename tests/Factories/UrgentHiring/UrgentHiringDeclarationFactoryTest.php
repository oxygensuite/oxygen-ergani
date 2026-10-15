<?php

namespace Tests\Factories\UrgentHiring;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\UrgentHiring\UrgentHiringDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;
use OxygenSuite\OxygenErgani\Models\UrgentHiring\UrgentHiringDeclaration;
use PHPUnit\Framework\TestCase;

class UrgentHiringDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = UrgentHiringDeclaration::factory()->make();

        $this->assertInstanceOf(UrgentHiringDeclaration::class, $declaration);
        $this->assertInstanceOf(UrgentHiringDeclarationFactory::class, UrgentHiringDeclaration::factory());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getAfm());
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $declaration->getStartTime());
        $this->assertSame([], $declaration->getSupplementaryInsuranceSelections());
    }

    public function testOutputFollowsSchemaOrder(): void
    {
        $keys = array_keys(UrgentHiringDeclaration::factory()->make()->toSortedArray());

        $this->assertSame('f_aa_pararthmatos', $keys[0]);
        $this->assertSame('f_comments', end($keys));
        $this->assertSame(array_search('f_kyria_asfalisi', $keys) + 1, array_search('EpikourikiSelections', $keys));
    }

    public function testStates(): void
    {
        $declaration = UrgentHiringDeclaration::factory()
            ->forBranch(2)
            ->asCorrection('FA-1', '01/10/2026')
            ->withSecondWorkingDay('02/10/2026', '08:00', '12:00')
            ->asEmployee()
            ->withCollectiveAgreement('ΕΓΣΣΕ')
            ->withSupplementaryInsurance(['001', '002'])
            ->make();

        $this->assertSame('2', $declaration->getBranchCode());
        $this->assertSame('FA-1', $declaration->getRelatedProtocol());
        $this->assertSame('01/10/2026', $declaration->getRelatedDate());
        $this->assertSame('02/10/2026', $declaration->getSecondWorkingDate());
        $this->assertSame('08:00', $declaration->getSecondStartTime());
        $this->assertSame('12:00', $declaration->getSecondEndTime());
        $this->assertSame('1', $declaration->getWorkerType());
        $this->assertSame('1', $declaration->getCollectiveAgreementApplies());
        $this->assertSame('ΕΓΣΣΕ', $declaration->getCollectiveAgreementComment());
        $this->assertCount(2, $declaration->getSupplementaryInsuranceSelections());
        $this->assertInstanceOf(SupplementaryInsuranceSelection::class, $declaration->getSupplementaryInsuranceSelections()[0]);
        $this->assertSame('002', $declaration->getSupplementaryInsuranceSelections()[1]->getSupplementaryInsuranceCode());
    }

    public function testCreatesMultipleDeclarations(): void
    {
        $declarations = UrgentHiringDeclaration::factory(3)->make();

        $this->assertCount(3, $declarations);
    }
}
