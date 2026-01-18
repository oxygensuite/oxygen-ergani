<?php

namespace Tests\Unit;

use OxygenSuite\OxygenErgani\Models\Model;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function test_with_defaults_fills_missing_keys_with_empty_string(): void
    {
        $model = new class extends Model {
            /** @var array<int, string> */
            protected array $expectedOrder = ['field_a', 'field_b', 'field_c'];
        };

        $model->set('field_a', 'value_a');
        $model->withDefaults();

        $this->assertSame('value_a', $model->get('field_a'));
        $this->assertSame('', $model->get('field_b'));
        $this->assertSame('', $model->get('field_c'));
    }

    public function test_with_defaults_does_not_override_existing_values(): void
    {
        $model = new class extends Model {
            /** @var array<int, string> */
            protected array $expectedOrder = ['field_a', 'field_b'];
        };

        $model->set('field_a', 'existing_value');
        $model->set('field_b', '0'); // Falsy but explicit value
        $model->withDefaults();

        $this->assertSame('existing_value', $model->get('field_a'));
        $this->assertSame('0', $model->get('field_b'));
    }

    public function test_with_defaults_uses_custom_defaults_when_defined(): void
    {
        $model = new class extends Model {
            /** @var array<int, string> */
            protected array $expectedOrder = ['field_a', 'field_b', 'array_field'];

            /** @var array<string, mixed> */
            protected array $defaults = [
                'array_field' => [],
            ];
        };

        $model->withDefaults();

        $this->assertSame('', $model->get('field_a'));
        $this->assertSame('', $model->get('field_b'));
        $this->assertSame([], $model->get('array_field'));
    }

    public function test_with_defaults_is_chainable(): void
    {
        $model = new class extends Model {
            /** @var array<int, string> */
            protected array $expectedOrder = ['field_a'];
        };

        $result = $model->withDefaults()->set('field_a', 'new_value');

        $this->assertSame($model, $result);
        $this->assertSame('new_value', $model->get('field_a'));
    }

    public function test_with_defaults_works_with_empty_expected_order(): void
    {
        $model = new class extends Model {
            // Empty expectedOrder
        };

        $model->set('custom_field', 'value');
        $model->withDefaults();

        // Should not affect anything
        $this->assertSame('value', $model->get('custom_field'));
        $this->assertSame(['custom_field' => 'value'], $model->attributes());
    }

    public function test_with_defaults_can_be_called_before_setting_values(): void
    {
        $model = new class extends Model {
            /** @var array<int, string> */
            protected array $expectedOrder = ['field_a', 'field_b'];
        };

        // Call withDefaults first, then override specific fields
        $model->withDefaults()->set('field_a', 'actual_value');

        $this->assertSame('actual_value', $model->get('field_a'));
        $this->assertSame('', $model->get('field_b'));
    }
}
