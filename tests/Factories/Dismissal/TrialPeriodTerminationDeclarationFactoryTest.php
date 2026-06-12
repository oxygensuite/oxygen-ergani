<?php

namespace Tests\Factories\Dismissal;

use OxygenSuite\OxygenErgani\Factories\Dismissal\TrialPeriodTerminationDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Dismissal\TrialPeriodTerminationDeclaration;
use PHPUnit\Framework\TestCase;

class TrialPeriodTerminationDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = TrialPeriodTerminationDeclarationFactory::new()->make();

        $this->assertInstanceOf(TrialPeriodTerminationDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = TrialPeriodTerminationDeclaration::factory();

        $this->assertInstanceOf(TrialPeriodTerminationDeclarationFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = TrialPeriodTerminationDeclarationFactory::new()->make();

        // Personal info
        $this->assertNotNull($declaration->getFirstName());
        $this->assertNotNull($declaration->getLastName());
        $this->assertNotNull($declaration->getAfm());
        $this->assertNotNull($declaration->getAmka());

        // E6LT specific - trial period termination
        $this->assertNotNull($declaration->getHiringDate());
        $this->assertNotNull($declaration->getTerminationDate());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = TrialPeriodTerminationDeclarationFactory::new()
            ->count(2)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(2, $declarations);
    }
}
