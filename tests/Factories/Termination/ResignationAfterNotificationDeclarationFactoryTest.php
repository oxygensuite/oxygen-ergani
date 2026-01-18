<?php

namespace Tests\Factories\Termination;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\ResignationAfterNotificationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Termination\ResignationAfterNotificationDeclaration;
use PHPUnit\Framework\TestCase;

class ResignationAfterNotificationDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()->make();

        $this->assertInstanceOf(ResignationAfterNotificationDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = ResignationAfterNotificationDeclaration::factory();

        $this->assertInstanceOf(ResignationAfterNotificationDeclarationFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $declarations = ResignationAfterNotificationDeclaration::factory(3)->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(ResignationAfterNotificationDeclaration::class, $declaration);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()->make();

        // Personal info
        $this->assertNotNull($declaration->getFirstName());
        $this->assertNotNull($declaration->getLastName());
        $this->assertNotNull($declaration->getFatherName());
        $this->assertNotNull($declaration->getMotherName());
        $this->assertNotNull($declaration->getBirthDate());
        $this->assertNotNull($declaration->getSex());

        // IDs
        $this->assertNotNull($declaration->getAfm());
        $this->assertNotNull($declaration->getAmka());
        $this->assertNotNull($declaration->getIdNumber());

        // Employment
        $this->assertNotNull($declaration->getHiringDate());
        $this->assertNotNull($declaration->getDepartureDate());

        // E5AO specific: HasSalary and HasNotificationReference
        $this->assertNotNull($declaration->getGrossSalary());
        $this->assertNotNull($declaration->getNotificationProtocol());
        $this->assertNotNull($declaration->getNotificationDate());
    }

    public function testAfmIsValid(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()->make();
        $afm = $declaration->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testAmkaIsValid(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()->make();
        $amka = $declaration->getAmka();

        $this->assertMatchesRegularExpression('/^\d{11}$/', $amka);
    }

    public function testDefaultsToGreekNational(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()->make();

        $this->assertEquals('001', $declaration->getNationality());
        $this->assertEquals('0', $declaration->getResPermitDirectAccess());
        $this->assertEquals('0', $declaration->getResPermitApproval());
        $this->assertEquals('0', $declaration->getSeasonalWorkVisa());
    }

    public function testDefaultsToFullTimeIndefinite(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()->make();

        $this->assertEquals((string) EmploymentType::INDEFINITE->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::FULL->value, $declaration->getEmploymentStatus());
    }

    public function testWithSalaryState(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()
            ->withSalary(2500.00)
            ->make();

        $this->assertEquals(2500.0, $declaration->getGrossSalary());
    }

    public function testWithNotificationReferenceState(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()
            ->withNotificationReference('E5O12345', '01/01/2025')
            ->make();

        $this->assertEquals('E5O12345', $declaration->getNotificationProtocol());
        $this->assertEquals('01/01/2025', $declaration->getNotificationDate());
    }

    public function testDefaultNotificationProtocolStartsWithE5O(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()->make();

        $this->assertStringStartsWith('Ε5Ο', $declaration->getNotificationProtocol());
    }

    public function testFixedTermState(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()
            ->fixedTerm('01/01/2025', '30/06/2025')
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals('01/01/2025', $declaration->getFixedTermFrom());
        $this->assertEquals('30/06/2025', $declaration->getFixedTermTo());
    }

    public function testPartTimeState(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()
            ->partTime()
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
    }

    public function testMaleState(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()
            ->male()
            ->make();

        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testFemaleState(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()
            ->female()
            ->make();

        $this->assertEquals((string) Sex::FEMALE->value, $declaration->getSex());
    }

    public function testChainingMultipleStates(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()
            ->partTime()
            ->male()
            ->withSalary(1800.00)
            ->withNotificationReference('E5O99999', '15/12/2024')
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
        $this->assertEquals(1800.0, $declaration->getGrossSalary());
        $this->assertEquals('E5O99999', $declaration->getNotificationProtocol());
        $this->assertEquals('15/12/2024', $declaration->getNotificationDate());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = ResignationAfterNotificationDeclarationFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(ResignationAfterNotificationDeclaration::class, $declaration);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()
            ->state(['f_eponymo' => 'ΤΕΣΤΟΠΟΥΛΟΣ'])
            ->make();

        $this->assertEquals('ΤΕΣΤΟΠΟΥΛΟΣ', $declaration->getLastName());
    }

    public function testExceptExcludesFields(): void
    {
        $declaration = ResignationAfterNotificationDeclarationFactory::new()
            ->except(['f_afm', 'f_amka'])
            ->make();

        $this->assertNull($declaration->getAfm());
        $this->assertNull($declaration->getAmka());
    }
}
