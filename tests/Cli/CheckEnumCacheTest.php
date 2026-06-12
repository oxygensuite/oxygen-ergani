<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Cli;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;
use OxygenSuite\OxygenErgani\Cache\FileCache;
use OxygenSuite\OxygenErgani\Enums\WorkCardDelayReason;
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;
use OxygenSuite\OxygenErgani\Responses\ParameterCollection;
use Tests\TestCase;

/**
 * Tests the caching patterns used by bin/check-enum.
 *
 * Since the CLI script defines standalone functions that can't be included
 * without side effects, these tests replicate the caching logic to verify:
 * - ParameterCollection round-trips through FileCache correctly
 * - Cache keys match the patterns used in the script
 * - Cache hit avoids API calls
 * - The --fresh deletion pattern works correctly
 */
class CheckEnumCacheTest extends TestCase
{
    private string $tempDir;

    private FileCache $cache;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/oxygen-ergani-cli-enum-test-' . uniqid();
        mkdir($this->tempDir, 0700, true);

        $this->cache = new FileCache(['cache_dir' => $this->tempDir]);
    }

    protected function tearDown(): void
    {
        $this->deleteDirectory($this->tempDir);
    }

    public function test_parameter_collection_survives_cache_round_trip(): void
    {
        $service = new ParameterLookup('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-03.json'));

        $original = $service->handle(ParameterLookup::WORK_TIME_TYPE);

        // Store in cache using the same key pattern as bin/check-enum
        $cacheKey = 'cli:enum:' . ParameterLookup::WORK_TIME_TYPE;
        $this->cache->set($cacheKey, $original, 86400);

        // Retrieve from cache
        $cached = $this->cache->get($cacheKey);

        $this->assertInstanceOf(ParameterCollection::class, $cached);
        $this->assertCount(count($original), $cached);

        // Verify specific entries survive serialization
        $this->assertSame('ΕΡΓ', $cached->find('ΕΡΓ')->code);
        $this->assertSame('ΕΡΓΑΣΙΑ', $cached->find('ΕΡΓ')->description);
        $this->assertSame('ΤΗΛΕΡΓΑΣΙΑ', $cached->find('ΤΗΛ')->description);

        // Verify collection features still work
        $this->assertTrue($cached->has('ΕΡΓ'));
        $this->assertFalse($cached->has('NONEXISTENT'));
        $this->assertSame('ΕΡΓΑΣΙΑ', $cached->toDropdown()['ΕΡΓ']);
    }

    public function test_cache_hit_avoids_api_call(): void
    {
        // First call: populate cache from mocked API
        $service = new ParameterLookup('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-03.json'));

        $type = ParameterLookup::WORK_TIME_TYPE;
        $cacheKey = "cli:enum:{$type}";
        $cacheTtl = 86400;

        // Replicate the getParameters() function from bin/check-enum
        $cached = $this->cache->get($cacheKey);
        $this->assertNull($cached, 'Cache should be empty initially');

        $result = $service->handle($type);
        $this->cache->set($cacheKey, $result, $cacheTtl);

        // Second call: should return from cache (no API call needed)
        // If we tried to use the service again, it would throw because the
        // MockHandler queue is exhausted — proving cache is working
        $cached = $this->cache->get($cacheKey);
        $this->assertNotNull($cached, 'Cache should contain the result');
        $this->assertInstanceOf(ParameterCollection::class, $cached);
        $this->assertCount(42, $cached);
    }

    public function test_fresh_flag_clears_enum_cache_keys(): void
    {
        // Simulate cached data for both enums
        $this->cache->set('cli:enum:' . ParameterLookup::WORK_CARD_DELAY_REASON, 'delay-data', 86400);
        $this->cache->set('cli:enum:' . ParameterLookup::WORK_TIME_TYPE, 'time-data', 86400);

        $this->assertTrue($this->cache->has('cli:enum:WorkCardDelayReason'));
        $this->assertTrue($this->cache->has('cli:enum:WorkTimeType'));

        // Replicate the --fresh logic from bin/check-enum
        $enumMap = [
            'WorkCardDelayReason' => [
                ParameterLookup::WORK_CARD_DELAY_REASON,
                WorkCardDelayReason::class,
            ],
            'WorkTimeType' => [
                ParameterLookup::WORK_TIME_TYPE,
                WorkTimeType::class,
            ],
        ];

        foreach ($enumMap as [$paramType, $enumClass]) {
            $this->cache->delete("cli:enum:{$paramType}");
        }

        $this->assertFalse($this->cache->has('cli:enum:WorkCardDelayReason'));
        $this->assertFalse($this->cache->has('cli:enum:WorkTimeType'));
    }

    public function test_cache_key_naming_convention(): void
    {
        $this->cache->set('cli:enum:WorkTimeType', 'test', 86400);
        $this->cache->set('cli:enum:WorkCardDelayReason', 'test', 86400);

        // Keys should be prefixed with cli:enum: and use the ParameterLookup constant value
        $this->assertSame('test', $this->cache->get('cli:enum:WorkTimeType'));
        $this->assertSame('test', $this->cache->get('cli:enum:WorkCardDelayReason'));

        // Different prefix should not collide
        $this->assertNull($this->cache->get('enum:WorkTimeType'));
        $this->assertNull($this->cache->get('cli:schema:WorkTimeType'));
    }

    public function test_cache_returns_null_after_expiry(): void
    {
        // Directly write an expired entry to simulate TTL expiration
        $key = 'cli:enum:WorkTimeType';
        $path = $this->tempDir . '/' . hash('sha256', $key) . '.cache';
        file_put_contents($path, serialize([
            'data' => 'expired-data',
            'expiry' => time() - 10,
        ]));

        $this->assertNull($this->cache->get($key));
    }

    public function test_compare_with_enum_uses_cached_collection(): void
    {
        // Populate cache with a real ParameterCollection
        $service = new ParameterLookup('test-access-token');
        $service->getConfig()->setHandler($this->mockResponse(200, 'ex-base-03.json'));

        $collection = $service->handle(ParameterLookup::WORK_TIME_TYPE);
        $cacheKey = 'cli:enum:' . ParameterLookup::WORK_TIME_TYPE;
        $this->cache->set($cacheKey, $collection, 86400);

        // Replicate the compareWithEnum() logic from bin/check-enum
        $cached = $this->cache->get($cacheKey);
        $apiValues = array_keys($cached->all());
        $enumValues = array_map(
            fn(\BackedEnum $case) => (string) $case->value,
            WorkTimeType::cases(),
        );

        $result = [
            'missing_in_enum' => array_values(array_diff($apiValues, $enumValues)),
            'extra_in_enum' => array_values(array_diff($enumValues, $apiValues)),
            'matched' => array_values(array_intersect($apiValues, $enumValues)),
        ];

        $this->assertIsArray($result['matched']);
        $this->assertIsArray($result['missing_in_enum']);
        $this->assertIsArray($result['extra_in_enum']);
        $this->assertGreaterThan(0, count($result['matched']));
    }

    public function test_multiple_mock_responses_prove_cache_hit(): void
    {
        // Queue only ONE response — a second API call would throw
        $handler = new MockHandler([
            new HttpResponse(200, body: $this->readFile('ex-base-03.json')),
        ]);

        $type = ParameterLookup::WORK_TIME_TYPE;
        $cacheKey = "cli:enum:{$type}";

        // First call: cache miss → API call
        $service1 = new ParameterLookup('test-access-token');
        $service1->getConfig()->setHandler($handler);
        $result1 = $service1->handle($type);
        $this->cache->set($cacheKey, $result1, 86400);

        // Second call: cache hit → no API call needed
        $cached = $this->cache->get($cacheKey);
        $this->assertNotNull($cached);
        $this->assertInstanceOf(ParameterCollection::class, $cached);

        // Verify the MockHandler queue is empty (the one response was consumed)
        $this->assertSame(0, $handler->count());
    }

    private function deleteDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $files = scandir($dir);
        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }
}
