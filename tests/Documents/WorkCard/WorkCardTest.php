<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\WorkCard;

use OxygenSuite\OxygenErgani\Enums\CardDetailType;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use Tests\TestCase;

class WorkCardTest extends TestCase
{
    public function test_work_card_submit(): void
    {
        $card = Card::factory()
            ->mainBranch()
            ->withComments('test-comments')
            ->withCardDetails([
                CardDetail::factory()->checkIn()->today()->make(),
            ])
            ->make();

        $workCard = new WorkCard('test-access-token');
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $response = $workCard->handle($card);

        $this->assertIsArray($response);
        $this->assertSame('92', $response[0]->id);
        $this->assertSame('ΕΥΣ92', $response[0]->protocol);
        $this->assertSame('04/05/2022 01:13', $response[0]->submissionDate->format('d/m/Y H:i'));
    }

    public function test_work_card_schema(): void
    {
        $workCard = new WorkCard('test-access-token');
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'work-card-schema.json'));
        $schema = $workCard->schema();

        $this->assertIsArray($schema);
        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_work_card_pdf(): void
    {
        $workCard = new WorkCard('test-access-token');
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'pdf.txt'));
        $response = $workCard->pdf('ΕΥΣ92', 20220504);

        $this->assertNotNull($response);
        $this->assertTrue($workCard->isSuccessful());
    }

    public function test_card_model(): void
    {
        $card = Card::factory()
            ->state([
                'f_afm_ergodoti' => '999999999',
                'f_aa' => 0,
                'f_comments' => 'test-comments',
            ])
            ->withCardDetails([
                CardDetail::factory()
                    ->checkIn()
                    ->make([
                        'f_afm' => '888888888',
                        'f_onoma' => 'John',
                        'f_eponymo' => 'Doe',
                        'f_reference_date' => '2025-02-21',
                        'f_date' => '2025-02-21 15:48:00',
                    ]),

                CardDetail::factory()
                    ->checkIn()
                    ->make([
                        'f_afm' => '777777777',
                        'f_onoma' => 'Jane',
                        'f_eponymo' => 'Doe',
                        'f_reference_date' => '2025-02-21',
                        'f_date' => '2025-02-21 15:48:00',
                    ]),
            ])
            ->make();

        $this->assertSame('999999999', $card->getEmployerTin());
        $this->assertSame(0, $card->getBranchCode());
        $this->assertSame('test-comments', $card->getComments());
        $this->assertNotNull($card->getDetails());
        $this->assertCount(2, $card->getDetails());

        $this->assertSame('888888888', $card->getDetail(0)->getTin());
        $this->assertSame('John', $card->getDetail(0)->getFirstName());
        $this->assertSame('Doe', $card->getDetail(0)->getLastName());
        $this->assertSame('2025-02-21', $card->getDetail(0)->getReferenceDate());
        $this->assertSame('2025-02-21 15:48:00', $card->getDetail(0)->getDate());
        $this->assertSame(CardDetailType::CHECK_IN, $card->getDetail(0)->getType());

        $this->assertSame('777777777', $card->getDetail(1)->getTin());
        $this->assertSame('Jane', $card->getDetail(1)->getFirstName());
        $this->assertSame('Doe', $card->getDetail(1)->getLastName());
        $this->assertSame('2025-02-21', $card->getDetail(1)->getReferenceDate());
        $this->assertSame('2025-02-21 15:48:00', $card->getDetail(1)->getDate());
        $this->assertSame(CardDetailType::CHECK_IN, $card->getDetail(1)->getType());
    }
}
