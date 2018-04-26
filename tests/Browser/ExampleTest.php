<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function testBasicExample()
    {
        $this->assertTrue(true);
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Filter by tags');
        });
    }
}
