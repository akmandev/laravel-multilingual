<?php

namespace OzanAkman\Multilingual\Tests\Unit;

use OzanAkman\Multilingual\Models\Locale;
use OzanAkman\Multilingual\Tests\TestCase;

class LocaleTest extends TestCase
{
    CONST PROJECT_DIR = __DIR__ . '/../..';

    protected $locale;

    public function setUp()
    {
        parent::setUp();
        $this->withFactories(self::PROJECT_DIR . '/database/factories');
        $this->locale = factory(Locale::class)->make();
    }

    public function test_locale_code_should_be_2_characters_long()
    {
        $this->assertEquals(strlen($this->locale->code), 2);
    }

    public function test_locale_is_disabled()
    {
        $this->locale->enabled = 0;
        $this->assertFalse($this->locale->enabled);
    }

    public function test_locale_is_enabled()
    {
        $this->locale->enabled = 1;
        $this->assertTrue($this->locale->enabled);
    }

    public function test_locale_is_default()
    {
        $this->locale->default = 1;
        $this->assertTrue($this->locale->default);
    }

    public function test_locale_cant_be_default_and_disabled()
    {
        $this->locale->enabled = 1;
        $this->locale->default = 1;
        $this->assertTrue($this->locale->enabled && $this->locale->default);
    }

    public function test_locale_name_is_not_null()
    {
        $this->locale->name = 'English';
        $this->assertNotNull($this->locale->name);
    }

    public function test_locale_native_name_is_not_null()
    {
        $this->locale->native_name = 'English';
        $this->assertNotNull($this->locale->native_name);
    }
}