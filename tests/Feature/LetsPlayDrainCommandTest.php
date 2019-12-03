<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LetsPlayDrainCommandTest extends TestCase
{
    /**
     * @test
     * Test a console command.
     *
     * @return void
     */
    public function it_should_check_input()
    {
        $this->artisan('drain:play')
            ->expectsQuestion('Do you want to continue', 'No')
            ->assertExitCode(0);
    }
}
