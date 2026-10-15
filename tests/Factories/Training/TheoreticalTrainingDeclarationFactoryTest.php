<?php

namespace Tests\Factories\Training;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Training\TheoreticalTrainingDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingDeclaration;
use PHPUnit\Framework\TestCase;

class TheoreticalTrainingDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = TheoreticalTrainingDeclaration::factory()->make();

        $this->assertInstanceOf(TheoreticalTrainingDeclaration::class, $declaration);
        $this->assertInstanceOf(TheoreticalTrainingDeclarationFactory::class, TheoreticalTrainingDeclaration::factory());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getAfm());
        $this->assertSame(20.0, $declaration->getWeeklyHours());
        $this->assertSame('', $declaration->getPracticeCompanyName());
    }

    public function testOutputContainsEveryField(): void
    {
        $array = TheoreticalTrainingDeclaration::factory()->make()->toSortedArray();
        $keys = array_keys($array);

        $this->assertCount(88, $keys);
        $this->assertSame('f_aa_pararthmatos', $keys[0]);
        $this->assertSame('f_young_file', $keys[87]);
        $this->assertSame('20,0', $array['f_week_hours']);
        $this->assertSame('', $array['f_daily_apodoxes']);
    }

    public function testWithPracticeState(): void
    {
        $declaration = TheoreticalTrainingDeclaration::factory()
            ->forBranch(2)
            ->asCorrection('E32-1', '01/10/2026')
            ->withPractice(240, 35.5)
            ->make();

        $this->assertSame('2', $declaration->getBranchCode());
        $this->assertSame('E32-1', $declaration->getRelatedProtocol());
        $this->assertSame('240', $declaration->getPracticeHours());
        $this->assertSame('30', $declaration->getInsuranceDays());
        $this->assertSame(35.5, $declaration->getDailyAllowance());
        $this->assertSame('35,50', $declaration->toSortedArray()['f_daily_apodoxes']);
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getPracticeCompanyAfm());
        $this->assertNotEmpty($declaration->getPracticeDateFrom());
    }

    public function testCreatesMultipleDeclarations(): void
    {
        $this->assertCount(3, TheoreticalTrainingDeclaration::factory(3)->make());
    }
}
