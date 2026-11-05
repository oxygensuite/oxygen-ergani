<?php

namespace Tests\Factories\CommunityProgram;

use OxygenSuite\OxygenErgani\Factories\CommunityProgram\CommunityProgramExtensionDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\CommunityProgram\CommunityProgramInterruptionDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramExtensionDeclaration;
use OxygenSuite\OxygenErgani\Models\CommunityProgram\CommunityProgramInterruptionDeclaration;
use PHPUnit\Framework\TestCase;

class CommunityProgramFollowUpFactoriesTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testExtensionFactory(): void
    {
        $this->assertInstanceOf(CommunityProgramExtensionDeclarationFactory::class, CommunityProgramExtensionDeclaration::factory());

        $declaration = CommunityProgramExtensionDeclaration::factory()
            ->forBranch(1)
            ->forDeclaration('ΠΠ59', '09/09/2026')
            ->extendedUntil('31/12/2027')
            ->make();

        $this->assertCount(13, $declaration->toSortedArray());
        $this->assertSame('1', $declaration->getBranchCode());
        $this->assertSame('ΠΠ59', $declaration->getRelatedProtocol());
        $this->assertSame('09/09/2026', $declaration->getRelatedDate());
        $this->assertSame('31/12/2027', $declaration->getExtensionDate());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getAfm());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getCertifierAfm());
    }

    public function testInterruptionFactory(): void
    {
        $this->assertInstanceOf(CommunityProgramInterruptionDeclarationFactory::class, CommunityProgramInterruptionDeclaration::factory());

        $declaration = CommunityProgramInterruptionDeclaration::factory()
            ->interruptedOn('01/10/2026', '002')
            ->make();

        $this->assertCount(14, $declaration->toSortedArray());
        $this->assertSame('01/10/2026', $declaration->getInterruptionDate());
        $this->assertSame('002', $declaration->getInterruptionReason());
    }
}
