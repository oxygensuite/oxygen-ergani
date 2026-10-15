<?php

namespace Tests\Factories\CommunityProgram;

use OxygenSuite\OxygenErgani\Factories\CommunityProgram\CommunityProgramDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramDeclaration;
use PHPUnit\Framework\TestCase;

class CommunityProgramDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = CommunityProgramDeclaration::factory()->make();

        $this->assertInstanceOf(CommunityProgramDeclaration::class, $declaration);
        $this->assertInstanceOf(CommunityProgramDeclarationFactory::class, CommunityProgramDeclaration::factory());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getAfm());
        $this->assertMatchesRegularExpression('/^\d{11}$/', $declaration->getAmka());
        $this->assertSame('1', $declaration->getEmploymentType());
        $this->assertNotEmpty($declaration->getStartDate());
        $this->assertNotEmpty($declaration->getEndDate());
    }

    public function testOutputContainsEveryField(): void
    {
        $keys = array_keys(CommunityProgramDeclaration::factory()->make()->toSortedArray());

        $this->assertCount(95, $keys);
        $this->assertSame('f_aa_pararthmatos', $keys[0]);
        $this->assertSame('f_foreign_file', $keys[94]);
    }

    public function testStates(): void
    {
        $declaration = CommunityProgramDeclaration::factory()
            ->forBranch(3)
            ->asCorrection('E31-1', '01/10/2026')
            ->indefinite()
            ->partTime('25')
            ->replacing('123456789', '01010101011')
            ->withUnemploymentBenefit('101213')
            ->make();

        $this->assertSame('3', $declaration->getBranchCode());
        $this->assertSame('E31-1', $declaration->getRelatedProtocol());
        $this->assertSame('01/10/2026', $declaration->getRelatedDate());
        $this->assertSame('0', $declaration->getEmploymentType());
        $this->assertSame('', $declaration->getStartDate());
        $this->assertSame('1', $declaration->getEmploymentStatus());
        $this->assertSame('25', $declaration->getWeeklyHours());
        $this->assertSame('1', $declaration->getReplacement());
        $this->assertSame('123456789', $declaration->getReplacedAfm());
        $this->assertSame('1', $declaration->getReceivesUnemploymentBenefit());
        $this->assertSame('101213', $declaration->getUnemploymentBenefitServiceCode());
    }

    public function testCreatesMultipleDeclarations(): void
    {
        $this->assertCount(3, CommunityProgramDeclaration::factory(3)->make());
    }
}
