<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Unit;

use DOMDocument;
use DOMElement;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Factories\Factory;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;
use OxygenSuite\OxygenErgani\Http\Documents\Modification\EmploymentModification;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use OxygenSuite\OxygenErgani\Models\Model;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;

/**
 * Sends factory-generated models through the real document classes (HTTP mocked) and validates the
 * produced payload against the official ERGANI XSD in xsd/, the same check ERGANI's server runs first.
 *
 * Covers every document in bin/schema-map.php (the map used by schema:check and submit:check).
 */
class XsdComplianceTest extends TestCase
{
    private const RUNS = 5;

    /**
     * Documents with a known, documented problem; keep this list empty whenever possible.
     *
     * @var array<string, string>
     */
    private const KNOWN_ISSUES = [];

    protected function setUp(): void
    {
        Factory::resetFaker();
    }

    /**
     * @return array<string, array{0: class-string<Documents>, 1: string, 2: callable(): Model}>
     */
    public static function documents(): array
    {
        $bin = dirname(__DIR__, 2) . '/bin';
        $schemaMap = require $bin . '/schema-map.php';
        $overrides = require $bin . '/factory-overrides.php';

        $cases = [];
        foreach ($schemaMap as $group) {
            foreach ($group['documents'] as $name => $documentClass) {
                $modelClass = self::modelFor($documentClass);
                $cases[$name] = [
                    $documentClass,
                    $group['xsd'],
                    $overrides[$name] ?? fn() => $modelClass::factory()->make(),
                ];
            }
        }

        // Extra variants that exercise optional structures
        $cases['E3N with unpredictable schedule'] = [HiringNew::class, 'E3N_v1.xsd',
            fn() => NewDeclaration::factory()->withUnpredictableSchedule()->make()];
        $cases['MA with supplementary insurance'] = [EmploymentModification::class, 'MA_v1.xsd',
            fn() => ModificationDeclaration::factory()->withSupplementaryInsurance(['201', '202'])->make()];

        return $cases;
    }

    /**
     * @param class-string<Documents> $documentClass
     * @param callable(): Model $make
     */
    #[DataProvider('documents')]
    public function test_factory_payload_is_valid_against_xsd(string $documentClass, string $xsd, callable $make): void
    {
        $name = $this->dataName();
        if (is_string($name) && isset(self::KNOWN_ISSUES[$name])) {
            $this->markTestSkipped(self::KNOWN_ISSUES[$name]);
        }

        $schema = $this->loadSchema(dirname(__DIR__, 2) . '/xsd/' . $xsd);

        for ($i = 0; $i < self::RUNS; $i++) {
            $document = new $documentClass('test-access-token', Environment::TEST);
            $mock = new MockHandler([new Response(200, [], '[]')]);
            $document->getConfig()->setHandler($mock);
            $document->handle($make());

            $body = json_decode((string) $mock->getLastRequest()->getBody(), true);

            $this->assertSame([], $this->validate($body, $schema), "{$documentClass} payload is not valid against {$xsd}");
        }
    }

    /**
     * @param class-string<Documents> $documentClass
     *
     * @return class-string<Model>
     */
    private static function modelFor(string $documentClass): string
    {
        $type = (new ReflectionMethod($documentClass, 'handle'))->getParameters()[0]->getType();
        $types = $type instanceof ReflectionUnionType ? $type->getTypes() : [$type];

        foreach ($types as $candidate) {
            if ($candidate instanceof ReflectionNamedType && is_subclass_of($candidate->getName(), Model::class)) {
                return $candidate->getName();
            }
        }

        throw new \LogicException("No model found for {$documentClass}::handle()");
    }

    /**
     * Load an XSD, stripping ^ and $ anchors from patterns: some ERGANI XSDs use them,
     * XSD regexes are implicitly anchored, and libxml would otherwise treat them as literals.
     */
    private function loadSchema(string $path): string
    {
        $xml = (string) file_get_contents($path);

        return (string) preg_replace_callback(
            '/(<xs:pattern\s+value=")([^"]*)(")/',
            fn(array $m) => $m[1] . preg_replace('/^\^|\$$/', '', $m[2]) . $m[3],
            $xml,
        );
    }

    /**
     * @param array<string, mixed> $body
     *
     * @return array<int, string>
     */
    private function validate(array $body, string $schema): array
    {
        $namespace = (string) (simplexml_load_string($schema)?->attributes()['targetNamespace'] ?? '');
        $outer = (string) array_key_first($body);
        $inner = (string) array_key_first($body[$outer]);
        $items = $body[$outer][$inner];

        $dom = new DOMDocument('1.0', 'utf-8');
        $root = $namespace !== '' ? $dom->createElementNS($namespace, $outer) : $dom->createElement($outer);
        $dom->appendChild($root);

        foreach (array_is_list($items) ? $items : [$items] as $item) {
            $element = $namespace !== '' ? $dom->createElementNS('', $inner) : $dom->createElement($inner);
            $root->appendChild($element);
            $this->appendChildren($dom, $element, $item);
        }

        $dom->loadXML((string) $dom->saveXML());

        $previous = libxml_use_internal_errors(true);
        $dom->schemaValidateSource($schema);
        $errors = array_map(fn($error) => trim($error->message), libxml_get_errors());
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return $errors;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function appendChildren(DOMDocument $dom, DOMElement $parent, array $data): void
    {
        foreach ($data as $key => $value) {
            $values = is_array($value) && array_is_list($value) ? $value : [$value];

            foreach ($values as $item) {
                $element = $dom->createElement($key);
                $parent->appendChild($element);

                if (is_array($item)) {
                    $this->appendChildren($dom, $element, $item);
                } else {
                    $element->appendChild($dom->createTextNode((string) $item));
                }
            }
        }
    }
}
