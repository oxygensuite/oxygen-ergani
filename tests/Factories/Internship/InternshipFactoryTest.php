<?php

namespace Tests\Factories\Internship;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Internship\InternshipDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Internship\InternshipScheduleChangeDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Internship\InternshipTerminationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipScheduleChangeDeclaration;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipTerminationDeclaration;
use PHPUnit\Framework\TestCase;

class InternshipFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testDeclarationFactory(): void
    {
        $declaration = InternshipDeclaration::factory()->make();

        $this->assertInstanceOf(InternshipDeclarationFactory::class, InternshipDeclaration::factory());
        $this->assertInstanceOf(InternshipDeclaration::class, $declaration);
        $this->assertArrayHasKey('f_insurance_agency', $declaration->toSortedArray());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getLegalRepresentativeAfm());
    }

    public function testTerminationFactory(): void
    {
        $declaration = InternshipTerminationDeclaration::factory()->make();

        $this->assertInstanceOf(InternshipTerminationDeclarationFactory::class, InternshipTerminationDeclaration::factory());
        $this->assertInstanceOf(InternshipTerminationDeclaration::class, $declaration);
        $this->assertCount(15, $declaration->toSortedArray());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getAfm());
        $this->assertMatchesRegularExpression('/^\d{3}$/', $declaration->getTerminationReason());
    }

    public function testTerminationStates(): void
    {
        $declaration = InternshipTerminationDeclaration::factory()
            ->forBranch(2)
            ->forDeclaration('ΠΡ123', '01/09/2026')
            ->terminatedOn('15/10/2026', '002')
            ->make();

        $this->assertSame('2', $declaration->getBranchCode());
        $this->assertSame('ΠΡ123', $declaration->getRelatedProtocol());
        $this->assertSame('01/09/2026', $declaration->getRelatedDate());
        $this->assertSame('15/10/2026', $declaration->getTerminationDate());
        $this->assertSame('002', $declaration->getTerminationReason());
    }

    public function testScheduleChangeFactory(): void
    {
        $declaration = InternshipScheduleChangeDeclaration::factory()->make();

        $this->assertInstanceOf(InternshipScheduleChangeDeclarationFactory::class, InternshipScheduleChangeDeclaration::factory());
        $this->assertInstanceOf(InternshipScheduleChangeDeclaration::class, $declaration);
        $this->assertCount(56, $declaration->toSortedArray());
        $this->assertSame(['from' => '09:00', 'to' => '15:00'], $declaration->getSchedule(1));
        $this->assertSame(['from' => '', 'to' => ''], $declaration->getSchedule(6));
        $this->assertSame(['from' => '', 'to' => ''], $declaration->getBreakSchedule(1));
    }

    public function testScheduleChangeStates(): void
    {
        $declaration = InternshipScheduleChangeDeclaration::factory()
            ->forBranch(1)
            ->forDeclaration('ΠΡ9', '01/09/2026')
            ->withSchedule('10:00', '14:00', [1, 3, 5])
            ->make();

        $this->assertSame('1', $declaration->getBranchCode());
        $this->assertSame('ΠΡ9', $declaration->getRelatedProtocol());
        $this->assertSame(['from' => '10:00', 'to' => '14:00'], $declaration->getSchedule(3));
        $this->assertSame(['from' => '', 'to' => ''], $declaration->getSchedule(2));
    }

    public function testCreatesMultiple(): void
    {
        $this->assertCount(3, InternshipTerminationDeclaration::factory(3)->make());
        $this->assertCount(2, InternshipScheduleChangeDeclaration::factory(2)->make());
    }
}
