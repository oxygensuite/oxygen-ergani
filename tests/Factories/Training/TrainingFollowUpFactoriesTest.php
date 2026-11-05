<?php

namespace Tests\Factories\Training;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Training\PracticalTrainingDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Training\TheoreticalTrainingEmployerChangeDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Training\TheoreticalTrainingInterruptionDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Training\PracticalTrainingDeclaration;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingEmployerChangeDeclaration;
use OxygenSuite\OxygenErgani\Models\Training\TheoreticalTrainingInterruptionDeclaration;
use PHPUnit\Framework\TestCase;

class TrainingFollowUpFactoriesTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testEmployerChangeFactory(): void
    {
        $this->assertInstanceOf(TheoreticalTrainingEmployerChangeDeclarationFactory::class, TheoreticalTrainingEmployerChangeDeclaration::factory());

        $declaration = TheoreticalTrainingEmployerChangeDeclaration::factory()
            ->forBranch(3)
            ->forDeclaration('ΠΠ65', '09/09/2026')
            ->withoutAme()
            ->make();

        $array = $declaration->toSortedArray();
        $this->assertCount(22, $array);
        $this->assertSame('3', $declaration->getBranchCode());
        $this->assertSame('ΠΠ65', $declaration->getRelatedProtocol());
        $this->assertSame('', $declaration->getPracticeCompanyAme());
        $this->assertSame('1', $declaration->getPracticeCompanyWithoutAme());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getPracticeCompanyAfm());
    }

    public function testInterruptionFactory(): void
    {
        $this->assertInstanceOf(TheoreticalTrainingInterruptionDeclarationFactory::class, TheoreticalTrainingInterruptionDeclaration::factory());

        $declaration = TheoreticalTrainingInterruptionDeclaration::factory()->make();
        $this->assertCount(13, $declaration->toSortedArray());
        $this->assertSame('', $declaration->getPracticeHours());

        $withPractice = TheoreticalTrainingInterruptionDeclaration::factory()->withPractice(80, 10)->make();
        $this->assertSame('80', $withPractice->getPracticeHours());
        $this->assertSame('10', $withPractice->getInsuranceDays());
    }

    public function testPracticalTrainingFactory(): void
    {
        $this->assertInstanceOf(PracticalTrainingDeclarationFactory::class, PracticalTrainingDeclaration::factory());

        $declaration = PracticalTrainingDeclaration::factory()
            ->asCorrection('E33-1', '01/11/2026')
            ->withTheoryProvider('094014201', 'ΚΔΒΜ123', 2)
            ->make();

        $keys = array_keys($declaration->toSortedArray());
        $this->assertCount(75, $keys);
        $this->assertSame('f_aa_pararthmatos', $keys[0]);
        $this->assertSame('f_young_file', $keys[74]);
        $this->assertSame('E33-1', $declaration->getRelatedProtocol());
        $this->assertSame('094014201', $declaration->getTheoryProviderAfm());
        $this->assertSame('ΚΔΒΜ123', $declaration->getTheoryEopepCode());
        $this->assertSame('2', $declaration->getTheoryProviderBranch());
    }

    public function testCreatesMultipleDeclarations(): void
    {
        $this->assertCount(2, PracticalTrainingDeclaration::factory(2)->make());
        $this->assertCount(2, TheoreticalTrainingEmployerChangeDeclaration::factory(2)->make());
    }
}
