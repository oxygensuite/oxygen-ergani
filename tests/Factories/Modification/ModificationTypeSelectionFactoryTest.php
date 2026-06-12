<?php

namespace Tests\Factories\Modification;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Modification\ModificationTypeSelectionFactory;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationTypeSelection;
use PHPUnit\Framework\TestCase;

class ModificationTypeSelectionFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesSelectionInstance(): void
    {
        $selection = ModificationTypeSelectionFactory::new()->make();

        $this->assertInstanceOf(ModificationTypeSelection::class, $selection);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = ModificationTypeSelection::factory();

        $this->assertInstanceOf(ModificationTypeSelectionFactory::class, $factory);
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $selection = ModificationTypeSelectionFactory::new()->make();

        $this->assertNotNull($selection->getModificationTypeCode());
    }

    public function testCodeState(): void
    {
        $selection = ModificationTypeSelectionFactory::new()
            ->code('001')
            ->make();

        $this->assertEquals('001', $selection->getModificationTypeCode());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $selections = ModificationTypeSelectionFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($selections);
        $this->assertCount(3, $selections);
        foreach ($selections as $selection) {
            $this->assertInstanceOf(ModificationTypeSelection::class, $selection);
        }
    }
}
