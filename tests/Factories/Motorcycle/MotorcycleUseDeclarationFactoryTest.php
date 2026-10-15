<?php

namespace Tests\Factories\Motorcycle;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Motorcycle\MotorcycleUseDeclarationFactory;
use OxygenSuite\OxygenErgani\Factories\Motorcycle\MotorcycleVehicleFactory;
use OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleUseDeclaration;
use OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleVehicle;
use PHPUnit\Framework\TestCase;

class MotorcycleUseDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = MotorcycleUseDeclaration::factory()->make();

        $this->assertInstanceOf(MotorcycleUseDeclaration::class, $declaration);
        $this->assertInstanceOf(MotorcycleUseDeclarationFactory::class, MotorcycleUseDeclaration::factory());
        $this->assertCount(1, $declaration->getVehicles());
        $this->assertInstanceOf(MotorcycleVehicle::class, $declaration->getVehicle(0));
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getLegalRepresentativeAfm());
        $this->assertMatchesRegularExpression('/^\d{9}$/', $declaration->getVehicle(0)->getAfm());
    }

    public function testOutputFollowsSchemaOrder(): void
    {
        $array = MotorcycleUseDeclaration::factory()->make()->toSortedArray();

        $this->assertSame(
            ['f_aa_pararthmatos', 'f_ypiresia_sepe', 'f_ypiresia_oaed', 'f_comments', 'f_afm_proswpoy', 'Mixanes'],
            array_keys($array),
        );
        $this->assertSame('f_licence_plate', array_key_first($array['Mixanes']['MixanesE4'][0]));
    }

    public function testStates(): void
    {
        $declaration = MotorcycleUseDeclaration::factory()
            ->forBranch(2)
            ->withVehicles(3)
            ->make();

        $this->assertSame('2', $declaration->getBranchCode());
        $this->assertCount(3, $declaration->getVehicles());

        $single = MotorcycleUseDeclaration::factory()->withVehicles(1)->make();
        $this->assertCount(1, $single->getVehicles());
    }

    public function testVehicleStates(): void
    {
        $vehicle = MotorcycleVehicleFactory::new()
            ->employerOwned()
            ->endingOn('31/12/2026')
            ->make();

        $this->assertSame('0', $vehicle->getOwner());
        $this->assertSame('31/12/2026', $vehicle->getEndDate());
        $this->assertInstanceOf(MotorcycleVehicleFactory::class, MotorcycleVehicle::factory());
    }

    public function testCreatesMultipleDeclarations(): void
    {
        $declarations = MotorcycleUseDeclaration::factory(3)->make();

        $this->assertCount(3, $declarations);
    }
}
