<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationSmokeTest extends TestCase
{
    public function test_homepage_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Rencanakan. Kerjakan.', false);
    }
}
