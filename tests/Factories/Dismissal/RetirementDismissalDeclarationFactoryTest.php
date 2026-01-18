<?php

namespace Tests\Factories\Dismissal;

use OxygenSuite\OxygenErgani\Factories\Dismissal\RetirementDismissalDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Dismissal\RetirementDismissalDeclaration;
use PHPUnit\Framework\TestCase;

class RetirementDismissalDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = RetirementDismissalDeclarationFactory::new()->make();

        $this->assertInstanceOf(RetirementDismissalDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = RetirementDismissalDeclaration::factory();

        $this->assertInstanceOf(RetirementDismissalDeclarationFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = RetirementDismissalDeclarationFactory::new()->make();

        // Personal info
        $this->assertNotNull($declaration->getFirstName());
        $this->assertNotNull($declaration->getLastName());
        $this->assertNotNull($declaration->getAfm());
        $this->assertNotNull($declaration->getAmka());

        // E6SXP specific
        $this->assertNotNull($declaration->getHiringDate());
        $this->assertNotNull($declaration->getDismissalDate());
        $this->assertNotNull($declaration->getGrossSalary());
        $this->assertNotNull($declaration->getCompensationAmount());
    }

    public function testWithSeveranceState(): void
    {
        $declaration = RetirementDismissalDeclarationFactory::new()
            ->withSeverance(15000.00)
            ->make();

        $this->assertEquals(15000.0, $declaration->getCompensationAmount());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = RetirementDismissalDeclarationFactory::new()
            ->count(2)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(2, $declarations);
    }
}
