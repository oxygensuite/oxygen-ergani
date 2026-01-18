<?php

namespace Tests\Factories\Termination;

use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\Termination\NotificationDeclarationFactory;
use OxygenSuite\OxygenErgani\Models\Termination\NotificationDeclaration;
use PHPUnit\Framework\TestCase;

class NotificationDeclarationFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesDeclarationInstance(): void
    {
        $declaration = NotificationDeclarationFactory::new()->make();

        $this->assertInstanceOf(NotificationDeclaration::class, $declaration);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = NotificationDeclaration::factory();

        $this->assertInstanceOf(NotificationDeclarationFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $declarations = NotificationDeclaration::factory(3)->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(NotificationDeclaration::class, $declaration);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $declaration = NotificationDeclarationFactory::new()->make();

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

        // E5O specific: notification methods
        $this->assertNotNull($declaration->getNotificationMethods());
    }

    public function testAfmIsValid(): void
    {
        $declaration = NotificationDeclarationFactory::new()->make();
        $afm = $declaration->getAfm();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testAmkaIsValid(): void
    {
        $declaration = NotificationDeclarationFactory::new()->make();
        $amka = $declaration->getAmka();

        $this->assertMatchesRegularExpression('/^\d{11}$/', $amka);
    }

    public function testDefaultsToGreekNational(): void
    {
        $declaration = NotificationDeclarationFactory::new()->make();

        $this->assertEquals('001', $declaration->getNationality());
        $this->assertEquals('0', $declaration->getResPermitDirectAccess());
        $this->assertEquals('0', $declaration->getResPermitApproval());
        $this->assertEquals('0', $declaration->getSeasonalWorkVisa());
    }

    public function testDefaultsToFullTimeIndefinite(): void
    {
        $declaration = NotificationDeclarationFactory::new()->make();

        $this->assertEquals((string) EmploymentType::INDEFINITE->value, $declaration->getEmploymentType());
        $this->assertEquals((string) EmploymentStatus::FULL->value, $declaration->getEmploymentStatus());
    }

    public function testWithNotificationMethodsState(): void
    {
        $declaration = NotificationDeclarationFactory::new()
            ->withNotificationMethods('Email, SMS, Phone')
            ->make();

        $this->assertEquals('Email, SMS, Phone', $declaration->getNotificationMethods());
    }

    public function testFixedTermState(): void
    {
        $declaration = NotificationDeclarationFactory::new()
            ->fixedTerm('01/01/2025', '30/06/2025')
            ->make();

        $this->assertEquals((string) EmploymentType::FIXED_TERM->value, $declaration->getEmploymentType());
        $this->assertEquals('01/01/2025', $declaration->getFixedTermFrom());
        $this->assertEquals('30/06/2025', $declaration->getFixedTermTo());
    }

    public function testPartTimeState(): void
    {
        $declaration = NotificationDeclarationFactory::new()
            ->partTime()
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
    }

    public function testMaleState(): void
    {
        $declaration = NotificationDeclarationFactory::new()
            ->male()
            ->make();

        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
    }

    public function testFemaleState(): void
    {
        $declaration = NotificationDeclarationFactory::new()
            ->female()
            ->make();

        $this->assertEquals((string) Sex::FEMALE->value, $declaration->getSex());
    }

    public function testChainingMultipleStates(): void
    {
        $declaration = NotificationDeclarationFactory::new()
            ->partTime()
            ->male()
            ->withNotificationMethods('Registered letter')
            ->make();

        $this->assertEquals((string) EmploymentStatus::PARTIAL->value, $declaration->getEmploymentStatus());
        $this->assertEquals((string) Sex::MALE->value, $declaration->getSex());
        $this->assertEquals('Registered letter', $declaration->getNotificationMethods());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $declarations = NotificationDeclarationFactory::new()
            ->count(3)
            ->make();

        $this->assertIsArray($declarations);
        $this->assertCount(3, $declarations);
        foreach ($declarations as $declaration) {
            $this->assertInstanceOf(NotificationDeclaration::class, $declaration);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $declaration = NotificationDeclarationFactory::new()
            ->state(['f_eponymo' => 'ΤΕΣΤΟΠΟΥΛΟΣ'])
            ->make();

        $this->assertEquals('ΤΕΣΤΟΠΟΥΛΟΣ', $declaration->getLastName());
    }

    public function testExceptExcludesFields(): void
    {
        $declaration = NotificationDeclarationFactory::new()
            ->except(['f_afm', 'f_amka'])
            ->make();

        $this->assertNull($declaration->getAfm());
        $this->assertNull($declaration->getAmka());
    }
}
