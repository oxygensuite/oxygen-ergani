<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\WorkCard;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Responses\WorkCardResponse;

class WorkCard extends Documents
{
    private const ACTION = 'WRKCardSE';

    /**
     * @param Card|Card[] $cards
     *
     * @return WorkCardResponse[]
     * @throws ErganiException
     */
    public function handle(Card|array $cards): array
    {
        if ($cards instanceof Card) {
            $cards = [$cards];
        }

        $body = ['Cards' => ['Card' => array_map(fn(Card $card) => $card->toSortedArray(), $cards)]];

        return $this->submit($body)->morphToArray(WorkCardResponse::class);
    }

    protected function action(): string
    {
        return self::ACTION;
    }
}
