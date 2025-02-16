<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents;

use OxygenSuite\OxygenErgani\Enums\CardDetailType;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard;
use OxygenSuite\OxygenErgani\Models\Card;
use OxygenSuite\OxygenErgani\Models\CardDetail;
use Tests\TestCase;

class WorkCardTest extends TestCase
{
    public function test_work_card_submit(): void
    {
        $card = Card::make()
            ->setEmployerTin('999999999')
            ->setBranchCode(0)
            ->setComments('test-comments')
            ->addDetails(
                CardDetail::make()
                    ->setTinNumber('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setType(CardDetailType::CHECK_IN)
                    ->setReferenceDate('2025-02-21')
                    ->setDate(date('Y-m-d\TH:i:s.uP'))
                    ->setReasonCode(null)
            );

        $workCard = new WorkCard('test-access-token');
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $response = $workCard->handle($card);

        $this->assertIsArray($response);
    }

    public function test_work_card_schema(): void
    {
        $workCard = new WorkCard("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'work-card-schema.json'));
        $workCard->schema();

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_work_card_pdf(): void
    {
        $workCard = new WorkCard("test-access-token");
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'pdf.txt'));
        $workCard->pdf("ΕΥΣ92", 19800410);

        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_card_model(): void
    {
        $card = Card::make()
            ->setEmployerTin('999999999')
            ->setBranchCode(0)
            ->setComments('test-comments')
            ->addDetails([
                CardDetail::make()
                    ->setTinNumber('888888888')
                    ->setFirstName('John')
                    ->setLastName('Doe')
                    ->setReferenceDate('2025-02-21')
                    ->setDate('2025-02-21 15:48:00')
                    ->setType(CardDetailType::CHECK_IN),

                CardDetail::make()
                    ->setTinNumber('777777777')
                    ->setFirstName('Jane')
                    ->setLastName('Doe')
                    ->setReferenceDate('2025-02-21')
                    ->setDate('2025-02-21 15:48:00')
                    ->setType(CardDetailType::CHECK_IN)
            ]);

        $this->assertSame('999999999', $card->getEmployerTin());
        $this->assertSame(0, $card->getBranchCode());
        $this->assertSame('test-comments', $card->getComments());
        $this->assertNotNull($card->getDetails());
        $this->assertCount(2, $card->getDetails());

        $this->assertSame('888888888', $card->getDetails(0)->getTinNumber());
        $this->assertSame('John', $card->getDetails(0)->getFirstName());
        $this->assertSame('Doe', $card->getDetails(0)->getLastName());
        $this->assertSame('2025-02-21', $card->getDetails(0)->getReferenceDate());
        $this->assertSame('2025-02-21 15:48:00', $card->getDetails(0)->getDate());
        $this->assertSame(CardDetailType::CHECK_IN, $card->getDetails(0)->getType());

        $this->assertSame('777777777', $card->getDetails(1)->getTinNumber());
        $this->assertSame('Jane', $card->getDetails(1)->getFirstName());
        $this->assertSame('Doe', $card->getDetails(1)->getLastName());
        $this->assertSame('2025-02-21', $card->getDetails(1)->getReferenceDate());
        $this->assertSame('2025-02-21 15:48:00', $card->getDetails(1)->getDate());
        $this->assertSame(CardDetailType::CHECK_IN, $card->getDetails(1)->getType());
    }
}
