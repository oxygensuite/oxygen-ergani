<?php

namespace Tests\Factories\ParentalLeave;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\ParentalLeave\ParentalLeaveDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveChild;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDate;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDeclaration;
use PHPUnit\Framework\TestCase;

class ParentalLeaveDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = ParentalLeaveDeclaration::factory()->make();

        $this->assertInstanceOf(ParentalLeaveDeclaration::class, $declaration);
        $this->assertInstanceOf(ParentalLeaveDeclarationFactory::class, ParentalLeaveDeclaration::factory());
        $this->assertCount(1, $declaration->getChildren());
        $this->assertInstanceOf(ParentalLeaveChild::class, $declaration->getChildren()[0]);
        $this->assertSame([], $declaration->getDates());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getAfm());
        $this->assertMatchesRegularExpression('/^\d{11}$/', $declaration->getAmka());
    }

    public function testOutputFollowsSchemaOrder(): void
    {
        $keys = array_keys(ParentalLeaveDeclaration::factory()->make()->toSortedArray());

        $this->assertSame('f_aa_pararthmatos', $keys[0]);
        $this->assertSame(['f_afm_proswpoy', 'Amkas', 'Dates'], array_slice($keys, -3));
    }

    public function testFlexibleState(): void
    {
        $declaration = ParentalLeaveDeclaration::factory()->flexible(1)->make();

        $this->assertSame('1', $declaration->getGrantMethod());
        $this->assertCount(1, $declaration->getDates());
        $this->assertInstanceOf(ParentalLeaveDate::class, $declaration->getDates()[0]);
        $this->assertSame('1', $declaration->getDates()[0]->getFullDay());
    }

    public function testStates(): void
    {
        $declaration = ParentalLeaveDeclaration::factory()
            ->forBranch(3)
            ->asCorrection('E14-1', '01/10/2026')
            ->partTime(25.0)
            ->singleParent('002')
            ->withChildren(2)
            ->make();

        $this->assertSame('3', $declaration->getBranchCode());
        $this->assertSame('E14-1', $declaration->getRelatedProtocol());
        $this->assertSame('01/10/2026', $declaration->getRelatedDate());
        $this->assertSame('1', $declaration->getEmploymentStatus());
        $this->assertSame(25.0, $declaration->getWeeklyHours());
        $this->assertSame('1', $declaration->getSingleParent());
        $this->assertSame('002', $declaration->getSingleParentCase());
        $this->assertCount(2, $declaration->getChildren());
    }

    public function testCreatesMultipleDeclarations(): void
    {
        $declarations = ParentalLeaveDeclaration::factory(3)->make();

        $this->assertCount(3, $declarations);
    }

    public function testCategoryIsSetWhenNotPaidByEntity(): void
    {
        $declaration = ParentalLeaveDeclaration::factory()->make();

        $this->assertSame('0', $declaration->getPaidByEntity());
        $this->assertNotSame('', $declaration->getCategory());
    }
}
