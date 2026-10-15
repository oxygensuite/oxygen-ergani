<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Hiring\Concerns;

use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;

trait TransformsSupplementaryInsurance
{
    /**
     * Transform SupplementaryInsuranceSelections to the ERGANI API nested format.
     *
     * @param array<string, mixed> $array The declaration array
     * @param string $nestedKey The API-specific nested key (e.g., 'EpikourikiSelectionsE3N')
     * @param string $wrapperKey Element that replaces SupplementaryInsuranceSelections (E3 forms: EpikourikiSelections, MA: Epikourikes)
     * @param string|null $codeKey Rename the f_kod_epikourikis code field (MA uses f_epikouriki_kod)
     *
     * @return array<string, mixed>
     */
    protected function transformSupplementaryInsuranceSelections(
        array $array,
        string $nestedKey,
        string $wrapperKey = 'EpikourikiSelections',
        ?string $codeKey = null,
    ): array {
        $key = 'SupplementaryInsuranceSelections';

        if (! isset($array[$key]) || ! is_array($array[$key])) {
            return $array;
        }

        $selections = $array[$key];

        $transformed = array_map(
            fn($selection) => $selection instanceof SupplementaryInsuranceSelection
                ? $selection->toSortedArray()
                : $selection,
            $selections,
        );

        if ($codeKey !== null) {
            $transformed = array_map(
                fn($selection) => is_array($selection) && array_key_exists('f_kod_epikourikis', $selection)
                    ? [$codeKey => $selection['f_kod_epikourikis']]
                    : $selection,
                $transformed,
            );
        }

        // ERGANI expects a single object when there's one selection,
        // and an array when there are multiple selections
        $value = count($transformed) === 1 ? $transformed[0] : $transformed;

        // Replace SupplementaryInsuranceSelections with the form's wrapper element
        // at the same position to preserve XSD field order
        $result = [];
        foreach ($array as $k => $v) {
            if ($k === $key) {
                $result[$wrapperKey] = [$nestedKey => $value];
            } else {
                $result[$k] = $v;
            }
        }

        return $result;
    }
}
