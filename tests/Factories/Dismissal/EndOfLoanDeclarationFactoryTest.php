<?php

namespace Tests\Factories\Dismissal;

use OxygenSuite\OxygenErgani\Factories\Dismissal\EndOfLoanDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Dismissal\EndOfLoanDeclaration;
use PHPUnit\Framework\TestCase;

class EndOfLoanDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = EndOfLoanDeclarationFactory::new()->make();

        $this->assertInstanceOf(EndOfLoanDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = EndOfLoanDeclaration::factory();

        $this->assertInstanceOf(EndOfLoanDeclarationFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = EndOfLoanDeclarationFactory::new()->make();

        // Personal info
        $this->assertNotNull($declaration->getFirstName());
        $this->assertNotNull($declaration->getLastName());
        $this->assertNotNull($declaration->getAfm());
        $this->assertNotNull($declaration->getAmka());

        // E6LD specific - loan details
        $this->assertNotNull($declaration->getLoanEndDate());
        $this->assertNotNull($declaration->getBorrowingCompanyAfm());
        $this->assertNotNull($declaration->getBorrowingCompanyName());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = EndOfLoanDeclarationFactory::new()
            ->count(2)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(2, $declarations);
    }
}
