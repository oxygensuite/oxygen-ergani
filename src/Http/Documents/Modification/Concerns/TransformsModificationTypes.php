<?php

namespace OxygenSuite\OxygenErgani\Http\Documents\Modification\Concerns;

use OxygenSuite\OxygenErgani\Models\Modification\ModificationTypeSelection;

trait TransformsModificationTypes
{
    /**
     * Transform ModificationTypeSelections to the ERGANI API nested format.
     *
     * @param array<string, mixed> $array The declaration array
     * @param string $nestedKey The API-specific nested key (e.g., 'TypesMetabolonMA')
     *
     * @return array<string, mixed>
     */
    protected function transformModificationTypeSelections(array $array, string $nestedKey): array
    {
        $key = 'ModificationTypeSelections';

        if (! isset($array[$key]) || ! is_array($array[$key])) {
            return $array;
        }

        $selections = $array[$key];

        $transformed = array_map(
            fn($selection) => $selection instanceof ModificationTypeSelection
                ? $selection->toSortedArray()
                : $selection,
            $selections,
        );

        // ERGANI expects a single object when there's one selection,
        // and an array when there are multiple selections
        $value = count($transformed) === 1 ? $transformed[0] : $transformed;

        // Replace ModificationTypeSelections with TypesMetabolon
        // at the same position to preserve XSD field order
        $result = [];
        foreach ($array as $k => $v) {
            if ($k === $key) {
                $result['TypesMetabolon'] = [$nestedKey => $value];
            } else {
                $result[$k] = $v;
            }
        }

        return $result;
    }
}
