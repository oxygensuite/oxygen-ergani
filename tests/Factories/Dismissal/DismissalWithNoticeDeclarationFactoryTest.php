<?php

namespace Tests\Factories\Dismissal;

use OxygenSuite\OxygenErgani\Enums\NoticePeriodMonths;
use OxygenSuite\OxygenErgani\Factories\Dismissal\DismissalWithNoticeDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithNoticeDeclaration;
use PHPUnit\Framework\TestCase;

class DismissalWithNoticeDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = DismissalWithNoticeDeclarationFactory::new()->make();

        $this->assertInstanceOf(DismissalWithNoticeDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = DismissalWithNoticeDeclaration::factory();

        $this->assertInstanceOf(DismissalWithNoticeDeclarationFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = DismissalWithNoticeDeclarationFactory::new()->make();

        // Personal info
        $this->assertNotNull($declaration->getFirstName());
        $this->assertNotNull($declaration->getLastName());
        $this->assertNotNull($declaration->getAfm());
        $this->assertNotNull($declaration->getAmka());

        // E6NMP specific - with notice period
        $this->assertNotNull($declaration->getHiringDate());
        $this->assertNotNull($declaration->getDismissalDate());
        $this->assertNotNull($declaration->getGrossSalary());
        $this->assertNotNull($declaration->getNoticeDate());
        $this->assertNotNull($declaration->getNoticePeriodMonths());
    }

    public function testNoticePeriodState(): void
    {
        $declaration = DismissalWithNoticeDeclarationFactory::new()
            ->noticePeriod(NoticePeriodMonths::TWO, '01/01/2025')
            ->make();

        $this->assertEquals('01/01/2025', $declaration->getNoticeDate());
        $this->assertEquals(2, $declaration->getNoticePeriodMonths());
    }

    public function testAsCollectiveDismissalState(): void
    {
        $declaration = DismissalWithNoticeDeclarationFactory::new()
            ->asCollectiveDismissal('ΑΠ-789012', '15/01/2025')
            ->make();

        $this->assertTrue($declaration->isCollectiveDismissal());
        $this->assertEquals('ΑΠ-789012', $declaration->getCollectiveDismissalNumber());
        $this->assertEquals('15/01/2025', $declaration->getCollectiveDismissalDate());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = DismissalWithNoticeDeclarationFactory::new()
            ->count(2)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(2, $declarations);
    }
}
