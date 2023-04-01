<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function login(array $credentials = ['email' => 'admin@admin.com' , 'password' => 'admin']){
        $response = $this->postJson(route('login' , $credentials));
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'role_name',
                'avatar',
                'permissions'
            ]
        ]);

        setToken(json_decode($response->getContent())->data->token);;
    }
}
