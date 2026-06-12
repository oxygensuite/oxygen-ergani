<?php

namespace Tests\Factories\Hiring;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Hiring\SupplementaryInsuranceSelectionFactory;
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;
use PHPUnit\Framework\TestCase;

class SupplementaryInsuranceSelectionFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesInstance(): void
    {
        $selection = SupplementaryInsuranceSelectionFactory::new()->make();

        $this->assertInstanceOf(SupplementaryInsuranceSelection::class, $selection);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = SupplementaryInsuranceSelection::factory();

        $this->assertInstanceOf(SupplementaryInsuranceSelectionFactory::class, $factory);
    }

    public function testDefinitionGeneratesValidCode(): void
    {
        $selection = SupplementaryInsuranceSelectionFactory::new()->make();

        $this->assertNotNull($selection->getSupplementaryInsuranceCode());
        $this->assertMatchesRegularExpression('/^\d{3}$/', $selection->getSupplementaryInsuranceCode());
    }

    public function testCodeStateMethod(): void
    {
        $selection = SupplementaryInsuranceSelectionFactory::new()
            ->code('999')
            ->make();

        $this->assertEquals('999', $selection->getSupplementaryInsuranceCode());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $selections = SupplementaryInsuranceSelectionFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($selections);
        $this->assertCount(3, $selections);
        foreach ($selections as $selection) {
            $this->assertInstanceOf(SupplementaryInsuranceSelection::class, $selection);
        }
    }
}
