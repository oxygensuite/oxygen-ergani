<?php

namespace OxygenSuite\OxygenErgani\Http\Documents;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Models\WTO;
use OxygenSuite\OxygenErgani\Responses\WTOResponse;

class DailyWorkTime extends Documents
{
    private const ACTION = 'WTODaily';

    /**
     * @throws ErganiException
     */
    public function handle(WTO|array $wto): array
    {
        if ($wto instanceof WTO) {
            $wto = [$wto];
        }

        $body = ['WTOS' => ['WTO' => array_map(fn (WTO $card) => $card->toSortedArray(), $wto)]];

        return $this->submit($body)->morphToArray(WTOResponse::class);
    }

    protected function action(): string
    {
        return self::ACTION;
    }
}
