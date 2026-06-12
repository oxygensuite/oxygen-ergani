<?php

namespace Tests\Unit;

use OxygenSuite\OxygenErgani\Responses\ParameterCollection;
use OxygenSuite\OxygenErgani\Responses\ParameterResponse;
use PHPUnit\Framework\TestCase;

class ParameterCollectionTest extends TestCase
{
    private function createParameter(string $code, string $description, ?string $extra = null): ParameterResponse
    {
        return new ParameterResponse([
            'Code' => $code,
            'Description' => $description,
            'Extra' => $extra,
        ]);
    }

    private function createCollection(): ParameterCollection
    {
        return new ParameterCollection([
            $this->createParameter('A', 'Alpha', 'Group1'),
            $this->createParameter('B', 'Beta', 'Group1'),
            $this->createParameter('C', 'Charlie', 'Group2'),
            $this->createParameter('D', 'Delta', 'Group2'),
        ]);
    }

    public function test_constructor_indexes_by_code(): void
    {
        $collection = $this->createCollection();

        $this->assertCount(4, $collection);
        $this->assertSame(['A', 'B', 'C', 'D'], $collection->codes());
    }

    public function test_constructor_skips_items_with_null_code(): void
    {
        $collection = new ParameterCollection([
            $this->createParameter('A', 'Alpha'),
            new ParameterResponse(['Code' => null, 'Description' => 'No Code']), // null code
            $this->createParameter('B', 'Beta'),
        ]);

        $this->assertCount(2, $collection);
        $this->assertSame(['A', 'B'], $collection->codes());
    }

    public function test_empty_collection(): void
    {
        $collection = new ParameterCollection();

        $this->assertCount(0, $collection);
        $this->assertTrue($collection->isEmpty());
        $this->assertFalse($collection->isNotEmpty());
        $this->assertNull($collection->first());
        $this->assertNull($collection->last());
    }

    public function test_find_returns_parameter_by_code(): void
    {
        $collection = $this->createCollection();

        $result = $collection->find('B');

        $this->assertInstanceOf(ParameterResponse::class, $result);
        $this->assertSame('B', $result->code);
        $this->assertSame('Beta', $result->description);
    }

    public function test_find_returns_null_for_nonexistent_code(): void
    {
        $collection = $this->createCollection();

        $this->assertNull($collection->find('NONEXISTENT'));
    }

    public function test_has_returns_true_for_existing_code(): void
    {
        $collection = $this->createCollection();

        $this->assertTrue($collection->has('A'));
        $this->assertTrue($collection->has('D'));
    }

    public function test_has_returns_false_for_nonexistent_code(): void
    {
        $collection = $this->createCollection();

        $this->assertFalse($collection->has('NONEXISTENT'));
    }

    public function test_search_finds_by_description_case_insensitive(): void
    {
        $collection = $this->createCollection();

        $results = $collection->search('alpha');

        $this->assertCount(1, $results);
        $this->assertTrue($results->has('A'));
    }

    public function test_search_finds_partial_matches(): void
    {
        $collection = $this->createCollection();

        // 'a' appears in Alpha, Beta, Delta, Charlie
        $results = $collection->search('a');

        $this->assertCount(4, $results);
    }

    public function test_search_returns_empty_collection_when_no_matches(): void
    {
        $collection = $this->createCollection();

        $results = $collection->search('xyz');

        $this->assertInstanceOf(ParameterCollection::class, $results);
        $this->assertTrue($results->isEmpty());
    }

    public function test_filter_with_callback(): void
    {
        $collection = $this->createCollection();

        $results = $collection->filter(fn(ParameterResponse $p) => $p->extra === 'Group1');

        $this->assertCount(2, $results);
        $this->assertTrue($results->has('A'));
        $this->assertTrue($results->has('B'));
        $this->assertFalse($results->has('C'));
    }

    public function test_to_dropdown_returns_code_description_array(): void
    {
        $collection = $this->createCollection();

        $dropdown = $collection->toDropdown();

        $this->assertSame([
            'A' => 'Alpha',
            'B' => 'Beta',
            'C' => 'Charlie',
            'D' => 'Delta',
        ], $dropdown);
    }

    public function test_to_dropdown_handles_null_description(): void
    {
        $collection = new ParameterCollection([
            new ParameterResponse(['Code' => 'X']), // null description
        ]);

        $dropdown = $collection->toDropdown();

        $this->assertSame(['X' => ''], $dropdown);
    }

    public function test_codes_returns_all_codes(): void
    {
        $collection = $this->createCollection();

        $this->assertSame(['A', 'B', 'C', 'D'], $collection->codes());
    }

    public function test_all_returns_all_items(): void
    {
        $collection = $this->createCollection();

        $all = $collection->all();

        $this->assertCount(4, $all);
        $this->assertArrayHasKey('A', $all);
        $this->assertArrayHasKey('D', $all);
    }

    public function test_first_returns_first_item(): void
    {
        $collection = $this->createCollection();

        $first = $collection->first();

        $this->assertInstanceOf(ParameterResponse::class, $first);
        $this->assertSame('A', $first->code);
    }

    public function test_last_returns_last_item(): void
    {
        $collection = $this->createCollection();

        $last = $collection->last();

        $this->assertInstanceOf(ParameterResponse::class, $last);
        $this->assertSame('D', $last->code);
    }

    public function test_is_empty_and_is_not_empty(): void
    {
        $empty = new ParameterCollection();
        $notEmpty = $this->createCollection();

        $this->assertTrue($empty->isEmpty());
        $this->assertFalse($empty->isNotEmpty());

        $this->assertFalse($notEmpty->isEmpty());
        $this->assertTrue($notEmpty->isNotEmpty());
    }

    public function test_count_returns_number_of_items(): void
    {
        $this->assertCount(0, new ParameterCollection());
        $this->assertCount(4, $this->createCollection());
    }

    public function test_iteration(): void
    {
        $collection = $this->createCollection();
        $codes = [];
        $descriptions = [];

        foreach ($collection as $code => $param) {
            $codes[] = $code;
            $descriptions[] = $param->description;
        }

        $this->assertSame(['A', 'B', 'C', 'D'], $codes);
        $this->assertSame(['Alpha', 'Beta', 'Charlie', 'Delta'], $descriptions);
    }

    public function test_array_access_offset_exists(): void
    {
        $collection = $this->createCollection();

        $this->assertTrue(isset($collection['A']));
        $this->assertFalse(isset($collection['NONEXISTENT']));
    }

    public function test_array_access_offset_get(): void
    {
        $collection = $this->createCollection();

        $this->assertInstanceOf(ParameterResponse::class, $collection['B']);
        $this->assertSame('Beta', $collection['B']->description);
        $this->assertNull($collection['NONEXISTENT']);
    }

    public function test_array_access_is_read_only(): void
    {
        $collection = $this->createCollection();

        // These operations should not throw but also should not modify
        $collection['NEW'] = $this->createParameter('NEW', 'New Item');
        unset($collection['A']);

        // Collection should remain unchanged
        $this->assertCount(4, $collection);
        $this->assertFalse($collection->has('NEW'));
        $this->assertTrue($collection->has('A'));
    }

    public function test_search_works_with_multibyte_characters(): void
    {
        // Greek characters to test mb_strpos vs strpos
        $collection = new ParameterCollection([
            $this->createParameter('ΕΡΓ', 'ΕΡΓΑΣΙΑ', 'Work'),
            $this->createParameter('ΤΗΛ', 'ΤΗΛΕΡΓΑΣΙΑ', 'Remote'),
            $this->createParameter('ΑΔ', 'Κανονική άδεια', 'Leave'),
        ]);

        // Search for Greek text
        $results = $collection->search('ΕΡΓΑΣΙΑ');
        $this->assertCount(2, $results);
        $this->assertTrue($results->has('ΕΡΓ'));
        $this->assertTrue($results->has('ΤΗΛ'));

        // Search for lowercase Greek (case-insensitive)
        $results = $collection->search('άδεια');
        $this->assertCount(1, $results);
        $this->assertTrue($results->has('ΑΔ'));
    }

    public function test_array_access_with_integer_offset(): void
    {
        // Test that integer offsets are cast to string
        $collection = new ParameterCollection([
            $this->createParameter('123', 'Numeric code'),
            $this->createParameter('456', 'Another numeric'),
        ]);

        // offsetExists with integer
        $this->assertTrue(isset($collection[123]));
        $this->assertFalse(isset($collection[999]));

        // offsetGet with integer
        $this->assertInstanceOf(ParameterResponse::class, $collection[123]);
        $this->assertSame('Numeric code', $collection[123]->description);
        $this->assertNull($collection[999]);
    }

    public function test_to_array_converts_objects_to_arrays(): void
    {
        $collection = $this->createCollection();

        $array = $collection->toArray();

        $this->assertIsArray($array);
        $this->assertCount(4, $array);
        $this->assertArrayHasKey('A', $array);
        $this->assertArrayHasKey('B', $array);

        // Each item should be an array, not an object
        $this->assertIsArray($array['A']);
        $this->assertSame([
            'code' => 'A',
            'description' => 'Alpha',
            'extra' => 'Group1',
        ], $array['A']);

        $this->assertSame([
            'code' => 'B',
            'description' => 'Beta',
            'extra' => 'Group1',
        ], $array['B']);
    }

    public function test_to_array_on_empty_collection(): void
    {
        $collection = new ParameterCollection();

        $array = $collection->toArray();

        $this->assertIsArray($array);
        $this->assertEmpty($array);
    }

    public function test_to_array_handles_null_values(): void
    {
        $collection = new ParameterCollection([
            new ParameterResponse(['Code' => 'X', 'Description' => null, 'Extra' => null]),
        ]);

        $array = $collection->toArray();

        $this->assertSame([
            'X' => [
                'code' => 'X',
                'description' => null,
                'extra' => null,
            ],
        ], $array);
    }

    public function test_parameter_response_to_array(): void
    {
        $response = $this->createParameter('ΕΡΓ', 'ΕΡΓΑΣΙΑ', 'ΕΡΓΑΣΙΑ-ΕΡΓΑΣΙΑ');

        $array = $response->toArray();

        $this->assertIsArray($array);
        $this->assertSame([
            'code' => 'ΕΡΓ',
            'description' => 'ΕΡΓΑΣΙΑ',
            'extra' => 'ΕΡΓΑΣΙΑ-ΕΡΓΑΣΙΑ',
        ], $array);
    }
}
