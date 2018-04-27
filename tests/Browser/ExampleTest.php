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
    public function test_if_admin_can_login_and_see_panel()
    {
        $this->seed('LaratrustSeeder');

        create('App\Category');
        create('App\Post');

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'superadministrator@app.com')
                ->type('password', 'password')
                ->press('Login')
                ->clickLink('Manage')
                ->assertVisible('#line-chart.chartjs-render-monitor')
                ->assertVisible('#doughnut-chart.chartjs-render-monitor')
                ->assertVisible('#bar-chart.chartjs-render-monitor');
        });

        $this->browse(function (Browser $browser) {
            $browser->visit('/blog')
                ->assertSee('Filter by tags');
        });
    }
}
