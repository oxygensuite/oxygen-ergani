<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Cli;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response as HttpResponse;
use OxygenSuite\OxygenErgani\Cache\FileCache;
use OxygenSuite\OxygenErgani\Http\Documents\LookupSubmissions;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTime;
use ReflectionClass;
use Tests\TestCase;

/**
 * Tests the caching patterns used by bin/check-schema.
 *
 * Since the CLI script defines standalone functions that can't be included
 * without side effects, these tests replicate the caching logic to verify:
 * - Schema field arrays round-trip through FileCache correctly
 * - Submissions arrays round-trip through FileCache correctly
 * - Cache keys match the patterns used in the script
 * - Cache hit avoids API calls
 * - The --fresh deletion pattern works correctly
 */
class CheckSchemaCacheTest extends TestCase
{
    private string $tempDir;

    private FileCache $cache;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/oxygen-ergani-cli-schema-test-' . uniqid();
        mkdir($this->tempDir, 0700, true);

        $this->cache = new FileCache(['cache_dir' => $this->tempDir]);
    }

    protected function tearDown(): void
    {
        $this->deleteDirectory($this->tempDir);
    }

    public function test_schema_fields_survive_cache_round_trip(): void
    {
        $workCard = new WorkCard('test-access-token');
        $workCard->getConfig()->setHandler($this->mockResponse(200, 'work-card-schema.json'));

        $schema = $workCard->schema();
        $fields = [];
        foreach ($schema['propertiesInfo'] as $prop) {
            $fields[] = $prop['name'];
        }

        // Store in cache using the same key pattern as bin/check-schema
        $shortName = (new ReflectionClass(WorkCard::class))->getShortName();
        $cacheKey = "cli:schema:{$shortName}";
        $this->cache->set($cacheKey, $fields, 86400);

        // Retrieve from cache
        $cached = $this->cache->get($cacheKey);

        $this->assertIsArray($cached);
        $this->assertSame($fields, $cached);
        $this->assertContains('f_afm_ergodoti', $cached);
        $this->assertContains('f_afm', $cached);
        $this->assertContains('f_eponymo', $cached);
        $this->assertContains('f_type', $cached);
    }

    public function test_wto_schema_fields_survive_cache_round_trip(): void
    {
        $dailyWorkTime = new DailyWorkTime('test-access-token');
        $dailyWorkTime->getConfig()->setHandler($this->mockResponse(200, 'wto-schema.json'));

        $schema = $dailyWorkTime->schema();
        $fields = [];
        foreach ($schema['propertiesInfo'] as $prop) {
            $fields[] = $prop['name'];
        }

        $shortName = (new ReflectionClass(DailyWorkTime::class))->getShortName();
        $cacheKey = "cli:schema:{$shortName}";
        $this->cache->set($cacheKey, $fields, 86400);

        $cached = $this->cache->get($cacheKey);

        $this->assertIsArray($cached);
        $this->assertSame($fields, $cached);
        $this->assertContains('f_aa_pararthmatos', $cached);
        $this->assertContains('f_from_date', $cached);
        $this->assertContains('f_to_date', $cached);
    }

    public function test_cache_hit_avoids_schema_api_call(): void
    {
        // Queue only ONE response
        $handler = new MockHandler([
            new HttpResponse(200, body: $this->readFile('work-card-schema.json')),
        ]);

        $workCard = new WorkCard('test-access-token');
        $workCard->getConfig()->setHandler($handler);

        $shortName = (new ReflectionClass(WorkCard::class))->getShortName();
        $cacheKey = "cli:schema:{$shortName}";

        // First call: cache miss → API call
        $this->assertNull($this->cache->get($cacheKey));

        $schema = $workCard->schema();
        $fields = [];
        foreach ($schema['propertiesInfo'] as $prop) {
            $fields[] = $prop['name'];
        }
        $this->cache->set($cacheKey, $fields, 86400);

        // Verify the mock was consumed
        $this->assertSame(0, $handler->count());

        // Second call: cache hit → no API call needed
        $cached = $this->cache->get($cacheKey);
        $this->assertNotNull($cached);
        $this->assertSame($fields, $cached);
    }

    public function test_submissions_survive_cache_round_trip(): void
    {
        $lookup = new LookupSubmissions('test-access-token');
        $lookup->getConfig()->setHandler($this->mockResponse(200, 'lookup-submissions.json'));

        $original = $lookup->handle();

        // Store in cache using the same key as bin/check-schema --coverage
        $this->cache->set('cli:submissions', $original, 86400);

        // Retrieve from cache
        $cached = $this->cache->get('cli:submissions');

        $this->assertIsArray($cached);
        $this->assertSame($original, $cached);
        $this->assertCount(35, $cached);

        $firstRow = $cached[array_key_first($cached)];
        $this->assertSame('E12', $firstRow['code']);
    }

    public function test_fresh_flag_clears_schema_cache_keys(): void
    {
        // Simulate cached data for multiple schema groups
        $this->cache->set('cli:schema:WorkCard', ['f_afm'], 86400);
        $this->cache->set('cli:schema:DailyWorkTime', ['f_date'], 86400);
        $this->cache->set('cli:schema:HiringNew', ['f_eponymo'], 86400);
        $this->cache->set('cli:submissions', [['code' => 'E12']], 86400);

        $this->assertTrue($this->cache->has('cli:schema:WorkCard'));
        $this->assertTrue($this->cache->has('cli:schema:DailyWorkTime'));
        $this->assertTrue($this->cache->has('cli:schema:HiringNew'));
        $this->assertTrue($this->cache->has('cli:submissions'));

        // Replicate the --fresh logic from bin/check-schema
        $docClasses = [WorkCard::class, DailyWorkTime::class];

        foreach ($docClasses as $docClass) {
            $this->cache->delete('cli:schema:' . (new ReflectionClass($docClass))->getShortName());
        }
        $this->cache->delete('cli:submissions');

        $this->assertFalse($this->cache->has('cli:schema:WorkCard'));
        $this->assertFalse($this->cache->has('cli:schema:DailyWorkTime'));
        $this->assertTrue($this->cache->has('cli:schema:HiringNew'), 'Unrelated keys should remain');
        $this->assertFalse($this->cache->has('cli:submissions'));
    }

    public function test_cache_key_naming_convention(): void
    {
        $this->cache->set('cli:schema:WorkCard', ['f_afm'], 86400);
        $this->cache->set('cli:submissions', ['data'], 86400);

        // Schema keys use the short class name
        $this->assertSame(['f_afm'], $this->cache->get('cli:schema:WorkCard'));

        // Submissions has a fixed key
        $this->assertSame(['data'], $this->cache->get('cli:submissions'));

        // Different prefixes should not collide
        $this->assertNull($this->cache->get('schema:WorkCard'));
        $this->assertNull($this->cache->get('cli:enum:WorkCard'));
        $this->assertNull($this->cache->get('submissions'));
    }

    public function test_schema_cache_key_uses_short_class_name(): void
    {
        // The script uses (new ReflectionClass($docClass))->getShortName() for cache keys
        $shortName = (new ReflectionClass(WorkCard::class))->getShortName();
        $this->assertSame('WorkCard', $shortName);

        $shortName = (new ReflectionClass(DailyWorkTime::class))->getShortName();
        $this->assertSame('DailyWorkTime', $shortName);

        $shortName = (new ReflectionClass(LookupSubmissions::class))->getShortName();
        $this->assertSame('LookupSubmissions', $shortName);
    }

    public function test_schema_union_fields_from_multiple_documents(): void
    {
        // In WTO group, multiple documents share one XSD.
        // The script unions their API fields. Test that cached fields support this.
        $fields1 = ['f_aa_pararthmatos', 'f_comments', 'f_from_date', 'f_afm', 'f_eponymo'];
        $fields2 = ['f_aa_pararthmatos', 'f_comments', 'f_from_date', 'f_afm', 'f_onoma'];

        $this->cache->set('cli:schema:DailyWorkTime', $fields1, 86400);
        $this->cache->set('cli:schema:DailyWorkTimeRetrospective', $fields2, 86400);

        $cached1 = $this->cache->get('cli:schema:DailyWorkTime');
        $cached2 = $this->cache->get('cli:schema:DailyWorkTimeRetrospective');

        // Build union like the script does
        $unionFields = [];
        foreach ([$cached1, $cached2] as $apiFields) {
            foreach ($apiFields as $field) {
                if (! in_array($field, $unionFields)) {
                    $unionFields[] = $field;
                }
            }
        }

        $this->assertSame(
            ['f_aa_pararthmatos', 'f_comments', 'f_from_date', 'f_afm', 'f_eponymo', 'f_onoma'],
            $unionFields,
        );
    }

    public function test_cache_returns_null_after_expiry(): void
    {
        $key = 'cli:schema:WorkCard';
        $path = $this->tempDir . '/' . hash('sha256', $key) . '.cache';
        file_put_contents($path, serialize([
            'data' => ['f_afm'],
            'expiry' => time() - 10,
        ]));

        $this->assertNull($this->cache->get($key));
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
