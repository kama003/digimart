<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DarkModeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the welcome page has dark mode classes.
     */
    public function test_welcome_page_has_dark_mode_classes(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('dark:bg-zinc-900', false);
        $response->assertSee('dark:text-white', false);
    }

    /**
     * Test that the login page has dark mode support.
     */
    public function test_login_page_has_dark_mode_classes(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        // The @fluxAppearance directive should inject dark mode JavaScript
        $response->assertSee('dark:text-zinc-400', false);
    }
}
