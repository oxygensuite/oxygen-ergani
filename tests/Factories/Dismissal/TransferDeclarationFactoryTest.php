<?php

namespace Tests\Factories\Dismissal;

use OxygenSuite\OxygenErgani\Factories\Dismissal\TransferDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Models\Dismissal\TransferDeclaration;
use PHPUnit\Framework\TestCase;

class TransferDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = TransferDeclarationFactory::new()->make();

        $this->assertInstanceOf(TransferDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = TransferDeclaration::factory();

        $this->assertInstanceOf(TransferDeclarationFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = TransferDeclarationFactory::new()->make();

        // Personal info
        $this->assertNotNull($declaration->getFirstName());
        $this->assertNotNull($declaration->getLastName());
        $this->assertNotNull($declaration->getAfm());
        $this->assertNotNull($declaration->getAmka());

        // E6M specific - transfer details
        $this->assertNotNull($declaration->getTransferDate());
        $this->assertNotNull($declaration->getTransferCompanyAfm());
        $this->assertNotNull($declaration->getTransferCompanyName());
    }

    public function testTransferDateState(): void
    {
        $declaration = TransferDeclarationFactory::new()
            ->transferDate('15/02/2025')
            ->make();

        $this->assertEquals('15/02/2025', $declaration->getTransferDate());
    }

    public function testToCompanyState(): void
    {
        $declaration = TransferDeclarationFactory::new()
            ->toCompany('123456789', 'ΕΤΑΙΡΕΙΑ ΤΕΣΤ')
            ->make();

        $this->assertEquals('123456789', $declaration->getTransferCompanyAfm());
        $this->assertEquals('ΕΤΑΙΡΕΙΑ ΤΕΣΤ', $declaration->getTransferCompanyName());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = TransferDeclarationFactory::new()
            ->count(2)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(2, $declarations);
    }
}
