<?php

namespace Tests\Factories\WorkCard;

use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Factories\WorkCard\CardDetailFactory;
use OxygenSuite\OxygenErgani\Factories\WorkCard\CardFactory;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use PHPUnit\Framework\TestCase;

class CardFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    public function testCreatesCardInstance(): void
    {
        $card = CardFactory::new()->make();

        $this->assertInstanceOf(Card::class, $card);
    }

    public function testModelHasFactoryMethod(): void
    {
        $factory = Card::factory();

        $this->assertInstanceOf(CardFactory::class, $factory);
    }

    public function testModelFactoryWithCount(): void
    {
        $cards = Card::factory(3)->make();

        $this->assertIsArray($cards);
        $this->assertCount(3, $cards);
        foreach ($cards as $card) {
            $this->assertInstanceOf(Card::class, $card);
        }
    }

    public function testDefinitionGeneratesRequiredFields(): void
    {
        $card = CardFactory::new()->make();

        $this->assertNotNull($card->getEmployerTin());
        $this->assertNotNull($card->getBranchCode());
        $this->assertNotEmpty($card->getDetails());
    }

    public function testEmployerAfmIsValid(): void
    {
        $card = CardFactory::new()->make();
        $afm = $card->getEmployerTin();

        $this->assertMatchesRegularExpression('/^\d{9}$/', $afm);
    }

    public function testBranchCodeIsWithinValidRange(): void
    {
        $card = CardFactory::new()->make();
        $branchCode = $card->getBranchCode();

        $this->assertIsInt($branchCode);
        $this->assertGreaterThanOrEqual(0, $branchCode);
        $this->assertLessThanOrEqual(99, $branchCode);
    }

    public function testDefaultIncludesOneCardDetail(): void
    {
        $card = CardFactory::new()->make();
        $details = $card->getDetails();

        $this->assertCount(1, $details);
        $this->assertInstanceOf(CardDetail::class, $details[0]);
    }

    public function testWithCommentsState(): void
    {
        $card = CardFactory::new()
            ->withComments('Test comments')
            ->make();

        $this->assertEquals('Test comments', $card->getComments());
    }

    public function testForBranchState(): void
    {
        $card = CardFactory::new()
            ->forBranch(5)
            ->make();

        $this->assertEquals(5, $card->getBranchCode());
    }

    public function testMainBranchState(): void
    {
        $card = CardFactory::new()
            ->mainBranch()
            ->make();

        $this->assertEquals(0, $card->getBranchCode());
    }

    public function testWithDetailsState(): void
    {
        $card = CardFactory::new()
            ->withDetails(3)
            ->make();

        $details = $card->getDetails();
        $this->assertCount(3, $details);
        foreach ($details as $detail) {
            $this->assertInstanceOf(CardDetail::class, $detail);
        }
    }

    public function testWithoutDetailsState(): void
    {
        $card = CardFactory::new()
            ->withoutDetails()
            ->make();

        $this->assertEmpty($card->getDetails());
    }

    public function testWithCardDetailsState(): void
    {
        $customDetails = [
            CardDetailFactory::new()->checkIn()->make(),
            CardDetailFactory::new()->checkOut()->make(),
        ];

        $card = CardFactory::new()
            ->withCardDetails($customDetails)
            ->make();

        $details = $card->getDetails();
        $this->assertCount(2, $details);
        $this->assertSame($customDetails[0], $details[0]);
        $this->assertSame($customDetails[1], $details[1]);
    }

    public function testChainingMultipleStates(): void
    {
        $card = CardFactory::new()
            ->mainBranch()
            ->withComments('Chained test')
            ->withDetails(2)
            ->make();

        $this->assertEquals(0, $card->getBranchCode());
        $this->assertEquals('Chained test', $card->getComments());
        $this->assertCount(2, $card->getDetails());
    }

    public function testCountCreatesMultipleInstances(): void
    {
        $cards = CardFactory::new()
            ->count(5)
            ->make();

        $this->assertIsArray($cards);
        $this->assertCount(5, $cards);
        foreach ($cards as $card) {
            $this->assertInstanceOf(Card::class, $card);
        }
    }

    public function testStateCanOverrideDefinition(): void
    {
        $card = CardFactory::new()
            ->state(['f_afm_ergodoti' => '123456789'])
            ->make();

        $this->assertEquals('123456789', $card->getEmployerTin());
    }

    public function testMakeCanOverrideState(): void
    {
        $card = CardFactory::new()
            ->state(['f_afm_ergodoti' => '111111111'])
            ->make(['f_afm_ergodoti' => '999999999']);

        $this->assertEquals('999999999', $card->getEmployerTin());
    }

    public function testExceptExcludesFields(): void
    {
        $card = CardFactory::new()
            ->except(['f_comments'])
            ->make();

        $this->assertNull($card->getComments());
    }

    public function testEachCardHasIndependentDetails(): void
    {
        $cards = CardFactory::new()
            ->withDetails(2)
            ->count(3)
            ->make();

        // Each card should have its own set of details
        foreach ($cards as $card) {
            $this->assertCount(2, $card->getDetails());
        }
    }
}
