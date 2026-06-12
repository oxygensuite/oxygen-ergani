<?php

namespace Tests\Factories\WorkCard;

use OxygenSuite\OxygenErgani\Enums\CardDetailType;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\WorkCard\CardDetailFactory;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use PHPUnit\Framework\TestCase;

class CardDetailFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesCardDetailInstance(): void
    {
        $cardDetail = CardDetailFactory::new()->make();

        $this->assertInstanceOf(CardDetail::class, $cardDetail);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = CardDetail::factory();

        $this->assertInstanceOf(CardDetailFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $cardDetails = CardDetail::factory(3)->make();

        $this->assertIsArray($cardDetails);
        $this->assertCount(3, $cardDetails);
        foreach ($cardDetails as $cardDetail) {
            $this->assertInstanceOf(CardDetail::class, $cardDetail);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $cardDetail = CardDetailFactory::new()->make();

        $this->assertNotNull($cardDetail->getTin());
        $this->assertNotNull($cardDetail->getLastName());
        $this->assertNotNull($cardDetail->getFirstName());
        $this->assertNotNull($cardDetail->getType());
        $this->assertNotNull($cardDetail->getReferenceDate());
        $this->assertNotNull($cardDetail->getDate());
    }

    public function testAfmIsValid(): void
    {
        $cardDetail = CardDetailFactory::new()->make();
        $afm = $cardDetail->getTin();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testTypeIsValidEnum(): void
    {
        $cardDetail = CardDetailFactory::new()->make();
        $type = $cardDetail->getType();

        $this->assertInstanceOf(CardDetailType::class, $type);
        $this->assertContains($type, [CardDetailType::CHECK_IN, CardDetailType::CHECK_OUT]);
    }

    public function testCheckInState(): void
    {
        $cardDetail = CardDetailFactory::new()
            ->checkIn()
            ->make();

        $this->assertEquals(CardDetailType::CHECK_IN, $cardDetail->getType());
    }

    public function testCheckOutState(): void
    {
        $cardDetail = CardDetailFactory::new()
            ->checkOut()
            ->make();

        $this->assertEquals(CardDetailType::CHECK_OUT, $cardDetail->getType());
    }

    public function testWithReasonCodeState(): void
    {
        $cardDetail = CardDetailFactory::new()
            ->withReasonCode('123')
            ->make();

        $this->assertEquals('123', $cardDetail->getReasonCode());
    }

    public function testTodayState(): void
    {
        $cardDetail = CardDetailFactory::new()
            ->today()
            ->make();

        $this->assertEquals(date('Y-m-d'), $cardDetail->getReferenceDate());
        $this->assertStringStartsWith(date('Y-m-d'), $cardDetail->getDate());
    }

    public function testForDateState(): void
    {
        $date = new \DateTime('2025-06-15 10:30:00');
        $cardDetail = CardDetailFactory::new()
            ->forDate($date)
            ->make();

        $this->assertEquals('2025-06-15', $cardDetail->getReferenceDate());
        $this->assertStringStartsWith('2025-06-15', $cardDetail->getDate());
    }

    public function testChainingMultipleStates(): void
    {
        $date = new \DateTime('2025-06-15 10:30:00');
        $cardDetail = CardDetailFactory::new()
            ->checkIn()
            ->forDate($date)
            ->withReasonCode('456')
            ->make();

        $this->assertEquals(CardDetailType::CHECK_IN, $cardDetail->getType());
        $this->assertEquals('2025-06-15', $cardDetail->getReferenceDate());
        $this->assertEquals('456', $cardDetail->getReasonCode());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $cardDetails = CardDetailFactory::new()
            ->count(5)
            ->make();

        $this->assertIsArray($cardDetails);
        $this->assertCount(5, $cardDetails);
        foreach ($cardDetails as $cardDetail) {
            $this->assertInstanceOf(CardDetail::class, $cardDetail);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $cardDetail = CardDetailFactory::new()
            ->state(['f_eponymo' => 'ΤΕΣΤΟΠΟΥΛΟΣ'])
            ->make();

        $this->assertEquals('ΤΕΣΤΟΠΟΥΛΟΣ', $cardDetail->getLastName());
    }

    public function testMakeCanOverrideState(): void
    {
        $cardDetail = CardDetailFactory::new()
            ->state(['f_eponymo' => 'STATE'])
            ->make(['f_eponymo' => 'OVERRIDE']);

        $this->assertEquals('OVERRIDE', $cardDetail->getLastName());
    }

    public function testExceptExcludesFields(): void
    {
        $cardDetail = CardDetailFactory::new()
            ->except(['f_aitiologia'])
            ->make();

        $this->assertNull($cardDetail->getReasonCode());
    }

    public function testReferenceDateFormatIsValid(): void
    {
        $cardDetail = CardDetailFactory::new()->make();
        $referenceDate = $cardDetail->getReferenceDate();

        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $referenceDate);
    }

    public function testDateFormatIsValid(): void
    {
        $cardDetail = CardDetailFactory::new()->make();
        $date = $cardDetail->getDate();

        // Format: YYYY-MM-DDTHH:MM:SS.uuuuuu+00:00
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{6}\+\d{2}:\d{2}$/', $date);
    }
}
