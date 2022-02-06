<?php declare(strict_types=1);

namespace Triggers\Tests;

use Orchestra\Testbench\TestCase;
use Triggers\Tests\World\Builder;
use Illuminate\Support\Facades\DB;

class Test extends TestCase
{
    /**
     * Setup the test environment.
     *
     */
    protected function setUp() : void
    {
        parent::setUp();

        Builder::create();

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        Builder::seed();
    }

    /** @test */
    public function it_can_register_triggers() : void
    {
        $this->assertCount(2, DB::table('users')->get());
        $this->assertCount(8, DB::table('posts')->get());

        $this->assertCount(2, DB::table('users')->where('posts', 1)->get());
    }
}
