<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testLogout(){
        $this->login();
        $response = $this
            ->withHeader('Authorization' , 'Bearer ' . getToken())
            ->postJson(route('logout'));

        $response->assertSuccessful();
    }
}
