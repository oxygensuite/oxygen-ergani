<?php

namespace Tests\Factories\Apprenticeship;

use OxygenSuite\OxygenErgani\Factories\Apprenticeship\ApprenticeshipDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Apprenticeship\ApprenticeshipDeclaration;
use PHPUnit\Framework\TestCase;

class ApprenticeshipDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = ApprenticeshipDeclaration::factory()->make();

        $this->assertInstanceOf(ApprenticeshipDeclaration::class, $declaration);
        $this->assertInstanceOf(ApprenticeshipDeclarationFactory::class, ApprenticeshipDeclaration::factory());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getAfm());
        $this->assertSame(650.0, $declaration->getGrossSalary());
    }

    public function testOutputContainsEveryField(): void
    {
        $array = ApprenticeshipDeclaration::factory()->make()->toSortedArray();
        $keys = array_keys($array);

        $this->assertCount(73, $keys);
        $this->assertSame('f_aa_pararthmatos', $keys[0]);
        $this->assertSame('f_young_file', $keys[72]);
        $this->assertSame('650,00', $array['f_apodoxes']);
        $this->assertContains($array['f_mea_epipedo_morfosis'], ['0', '1', '2', '3', '4']);
        $this->assertLessThanOrEqual(20, mb_strlen($array['f_mer_organization']));
    }

    public function testStates(): void
    {
        $declaration = ApprenticeshipDeclaration::factory()
            ->forBranch(4)
            ->asCorrection('E34-1', '01/11/2026')
            ->asMinor('ΒΑ999999')
            ->make();

        $this->assertSame('4', $declaration->getBranchCode());
        $this->assertSame('E34-1', $declaration->getRelatedProtocol());
        $this->assertSame('01/11/2026', $declaration->getRelatedDate());
        $this->assertSame('ΒΑ999999', $declaration->getMinorBookNumber());
    }

    public function testCreatesMultipleDeclarations(): void
    {
        $this->assertCount(3, ApprenticeshipDeclaration::factory(3)->make());
    }
}
