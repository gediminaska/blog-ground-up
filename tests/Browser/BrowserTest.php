<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function tests_if_user_can_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'Meooiw')
                ->type('email', 'someone@outlook.com')
                ->type('password', 'secret')
                ->type('password_confirmation', 'secret')
                ->press('Register');
            $this->assertTrue(count(User::all()) == 1);
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function tests_if_admin_can_login_and_see_charts()
    {
        $this->seed('LaratrustSeeder');

        create('App\Category');
        create('App\Post');

        $this->browse(function (Browser $browser) {
            $browser->logout()->visit('/login')
                ->type('email', 'superadministrator@app.com')
                ->type('password', 'password')
                ->press('Login')
                ->clickLink('Manage')
                ->assertVisible('#line-chart.chartjs-render-monitor')
                ->assertVisible('#doughnut-chart.chartjs-render-monitor')
                ->assertVisible('#bar-chart.chartjs-render-monitor');
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function test_if_typing_and_new_comments_get_broadcast()
    {
        $this->seed('LaratrustSeeder');

        create('App\Category');
        create('App\Post');
        create('App\Comment');
        create('App\Comment');

        $this->browse(function (Browser $first, Browser $second, Browser $third) {
            $post = create('App\Post');

            $first->visit('/')
                ->clickLink('Comments')
                ->resize(1600, 1080);
            $third->loginAs(User::query()->find(2))
                ->visit('/blog/' . $post->slug);

            $second->loginAs(User::query()->find(1))
                ->visit('/blog/' . $post->slug)
                ->type('body', 'test comment');

            $third->waitForText('Superadministrator is typing');

            $second->click('.phpdebugbar-close-btn')
                ->press('button')
                ->pause(1000)
                ->assertSee('Author');

            $first->waitForText('test comment');
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function tests_if_post_can_be_created_and_updated()
    {
        $this->seed('LaratrustSeeder');

        create('App\Category');

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::query()->find(1))
                ->visit('/manage/posts/create')
                ->type('tag', 'new-tag')
                ->pause(500)
                ->press('New tag')
                ->click('.select2-selection__rendered')
                ->click('.select2-results__option')
                ->type('title', 'test title')
                ->pause(300)
                ->attach('images[]',  __DIR__.'/logo.png')
                ->type('body', 'Lorem ipsum text. Lorem ipsum text.')
                ->press('Save Draft')
                ->clickLink('Drafts (1)')
                ->clickLink('Continue writing')
                ->type('title', 'test title edited')
                ->press('Edit')
                ->type('slug-edit', 'test title changed')
                ->press('Save')
                ->pause(200)
                ->press('Publish')
                ->visit('/blog/test-title-changed')
                ->assertVisible('.blog-single-page-image')
                ->assertSee('test title edited')
                ->assertSee('Lorem ipsum text.')
                ->assertSee('new-tag');
        });
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function tests_if_filter_works()
    {
        $this->seed('LaratrustSeeder');

        create('App\Category');

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::query()->find(1));
                for($i=1; $i<3; $i++) {
                    $browser->visit('/manage/posts/create')
                        ->type('tag', 'new-tag' . $i)
                        ->pause(500)
                        ->press('New tag')
                        ->click('.select2-selection__rendered')
                        ->click('.select2-results__option:last-child')
                        ->type('title', $i . 'test title')
                        ->pause(500)
                        ->type('body', $i . 'Lorem ipsum text Lorem ipsum text.')
                        ->press('Publish');
                }
                $browser->visit('/blog')
                ->click('.dropdown-trigger')
                ->check('filter[]')
                ->press('Filter')
                ->assertSee('1test title')
                ->assertSee('1Lorem')
                ->assertSee('new-tag1')
                ->assertDontSee('2test title')
                ->click('.dropdown-trigger')
                ->pause(500)
                ->uncheck("input[name='filter[]'][value='new-tag1']")
                ->check("input[name='filter[]'][value='new-tag2']")
                ->press('Filter')
                ->assertSee('2test title')
                ->assertSee('2Lorem')
                ->assertSee('new-tag2')
                ->assertDontSee('1test title');
        });
    }
}