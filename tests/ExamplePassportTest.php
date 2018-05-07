<?php

use Tests\PassportTestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExamplePassportTest extends PassportTestCase
{
    use DatabaseTransactions;

    protected $scopes = ['restricted-scope'];

    public function testRestrictedRoute()
    {
        $this->get('/api/user')
            ->assertResponseStatus(401);
    }

    public function testUnrestrictedRoute()
    {
        $this->get('/api/restricted')
            ->assertResponseOk();
    }
}
