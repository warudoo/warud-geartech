<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $compiledPath = storage_path('framework/testing/views/'.md5(static::class.'-'.spl_object_id($this).'-'.microtime(true)));

        File::ensureDirectoryExists($compiledPath);

        config()->set('view.compiled', $compiledPath);
    }
}
