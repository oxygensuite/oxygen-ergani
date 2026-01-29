<?php

namespace Tests\Cache;

use DateInterval;
use OxygenSuite\OxygenErgani\Cache\FileCache;
use PHPUnit\Framework\TestCase;

class FileCacheTest extends TestCase
{
    private string $tempDir;
    private FileCache $cache;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/oxygen-ergani-cache-test-' . uniqid();
        mkdir($this->tempDir, 0700, true);

        $this->cache = new FileCache(['cache_dir' => $this->tempDir]);
    }

    protected function tearDown(): void
    {
        $this->deleteDirectory($this->tempDir);
        FileCache::resetDirectory();
    }

    public function test_get_returns_default_for_missing_key(): void
    {
        $this->assertNull($this->cache->get('missing'));
        $this->assertSame('fallback', $this->cache->get('missing', 'fallback'));
    }

    public function test_set_and_get(): void
    {
        $this->assertTrue($this->cache->set('key', 'value'));
        $this->assertSame('value', $this->cache->get('key'));
    }

    public function test_set_with_null_ttl_stores_forever(): void
    {
        $this->cache->set('key', 'value', null);
        $this->assertSame('value', $this->cache->get('key'));
    }

    public function test_set_with_zero_ttl_deletes(): void
    {
        $this->cache->set('key', 'value');
        $this->cache->set('key', 'new', 0);
        $this->assertNull($this->cache->get('key'));
    }

    public function test_set_with_negative_ttl_deletes(): void
    {
        $this->cache->set('key', 'value');
        $this->cache->set('key', 'new', -1);
        $this->assertNull($this->cache->get('key'));
    }

    public function test_set_with_date_interval(): void
    {
        $this->cache->set('key', 'value', new DateInterval('PT1H'));
        $this->assertSame('value', $this->cache->get('key'));
    }

    public function test_delete(): void
    {
        $this->cache->set('key', 'value');
        $this->assertTrue($this->cache->delete('key'));
        $this->assertNull($this->cache->get('key'));
    }

    public function test_delete_nonexistent_key(): void
    {
        $this->assertTrue($this->cache->delete('nonexistent'));
    }

    public function test_clear(): void
    {
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');
        $this->assertTrue($this->cache->clear());
        $this->assertNull($this->cache->get('key1'));
        $this->assertNull($this->cache->get('key2'));
    }

    public function test_has(): void
    {
        $this->assertFalse($this->cache->has('key'));
        $this->cache->set('key', 'value');
        $this->assertTrue($this->cache->has('key'));
    }

    public function test_has_with_null_value(): void
    {
        $this->cache->set('key', null);
        $this->assertTrue($this->cache->has('key'));
    }

    public function test_get_multiple(): void
    {
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');

        $result = $this->cache->getMultiple(['key1', 'key2', 'key3'], 'default');

        $this->assertSame('value1', $result['key1']);
        $this->assertSame('value2', $result['key2']);
        $this->assertSame('default', $result['key3']);
    }

    public function test_set_multiple(): void
    {
        $this->assertTrue($this->cache->setMultiple(['key1' => 'value1', 'key2' => 'value2']));

        $this->assertSame('value1', $this->cache->get('key1'));
        $this->assertSame('value2', $this->cache->get('key2'));
    }

    public function test_delete_multiple(): void
    {
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');
        $this->cache->set('key3', 'value3');

        $this->assertTrue($this->cache->deleteMultiple(['key1', 'key2']));

        $this->assertNull($this->cache->get('key1'));
        $this->assertNull($this->cache->get('key2'));
        $this->assertSame('value3', $this->cache->get('key3'));
    }

    public function test_stores_various_types(): void
    {
        $this->cache->set('string', 'hello');
        $this->cache->set('int', 42);
        $this->cache->set('float', 3.14);
        $this->cache->set('bool', true);
        $this->cache->set('array', ['a' => 1]);

        $this->assertSame('hello', $this->cache->get('string'));
        $this->assertSame(42, $this->cache->get('int'));
        $this->assertSame(3.14, $this->cache->get('float'));
        $this->assertTrue($this->cache->get('bool'));
        $this->assertSame(['a' => 1], $this->cache->get('array'));
    }

    public function test_overwrite_existing_key(): void
    {
        $this->cache->set('key', 'original');
        $this->cache->set('key', 'updated');
        $this->assertSame('updated', $this->cache->get('key'));
    }

    public function test_expired_entry_returns_default(): void
    {
        $this->cache->set('key', 'value', 1);

        // Manually write an expired entry
        $directory = $this->cache->getDirectory();
        $path = $directory . '/' . hash('sha256', 'expired') . '.cache';
        file_put_contents($path, serialize([
            'data' => 'old-value',
            'expiry' => time() - 10,
        ]));

        $this->assertNull($this->cache->get('expired'));
    }

    public function test_instance_directory_takes_priority(): void
    {
        FileCache::setDirectory('/some/other/dir');

        $cache = new FileCache(['cache_dir' => $this->tempDir]);

        $this->assertSame($this->tempDir, $cache->getDirectory());
    }

    public function test_static_directory(): void
    {
        FileCache::setDirectory('/custom/dir');
        $this->assertSame('/custom/dir', FileCache::dir());
    }

    public function test_reset_directory(): void
    {
        FileCache::setDirectory('/custom/dir');
        FileCache::resetDirectory();

        $this->assertStringEndsWith('.cache/data', FileCache::dir());
    }

    public function test_default_directory(): void
    {
        FileCache::resetDirectory();
        $this->assertStringEndsWith('.cache/data', FileCache::dir());
    }

    public function test_creates_directory_on_set(): void
    {
        $newDir = $this->tempDir . '/sub/dir';
        $cache = new FileCache(['cache_dir' => $newDir]);

        $cache->set('key', 'value');

        $this->assertDirectoryExists($newDir);
        $this->assertSame('value', $cache->get('key'));
    }

    public function test_corrupted_cache_file_returns_default(): void
    {
        $directory = $this->cache->getDirectory();
        $path = $directory . '/' . hash('sha256', 'bad') . '.cache';
        file_put_contents($path, 'not-serialized-data');

        $this->assertNull($this->cache->get('bad'));
    }

    public function test_clear_expired_removes_only_expired(): void
    {
        $this->cache->set('forever', 'stays');
        $this->cache->set('long', 'stays', 3600);

        // Write an expired entry directly
        $directory = $this->cache->getDirectory();
        $path = $directory . '/' . hash('sha256', 'expired') . '.cache';
        file_put_contents($path, serialize([
            'data' => 'gone',
            'expiry' => time() - 10,
        ]));

        $removed = $this->cache->clearExpired();

        $this->assertSame(1, $removed);
        $this->assertSame('stays', $this->cache->get('forever'));
        $this->assertSame('stays', $this->cache->get('long'));
        $this->assertFalse(file_exists($path));
    }

    public function test_clear_expired_removes_corrupted_files(): void
    {
        $directory = $this->cache->getDirectory();
        $path = $directory . '/' . hash('sha256', 'corrupted') . '.cache';
        file_put_contents($path, 'not-valid-data');

        $removed = $this->cache->clearExpired();

        $this->assertSame(1, $removed);
        $this->assertFalse(file_exists($path));
    }

    public function test_clear_expired_returns_zero_when_nothing_expired(): void
    {
        $this->cache->set('key', 'value');
        $this->assertSame(0, $this->cache->clearExpired());
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
