<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testShowAllUsers(): void
    {
        $this->login();
        $response = $this
            ->withHeader('Authorization' , 'Bearer ' . getToken())
            ->getJson(route('users.index'));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'email' ,
                    'role_id',
                    'role_name',
                    'avatar'
                ]
            ]
        ]);
    }

    public function testOneUser(){
        $this->login();

        $user = User::create([
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => fake()->email(),
            'status' => 1,
        ]);

        $user->assignRole('admin');
        $response = $this
            ->withHeader('Authorization' , 'Bearer ' . getToken())
            ->getJson(route('users.show' , ['user' => $user->id]));

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'role_id',
                'role_name',
                'avatar'
            ]
        ]);
    }

    public function testStore(){
        $this->login();

        config()->set('medialibrary.url_generator', 'Tests\Feature\FakeStorageUrlGenerator');
// now you can get your view without an exception being thrown
        $user = User::create([
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => fake()->email(),
            'status' => 1,
        ]);
        $user = new User();

        $user->assignRole('admin');

        $response = $this
            ->withHeader('Authorization' , 'Bearer ' . getToken())
            ->postJson(route('users.store' , [
                'name' => fake()->name(),
                'email' => fake()->email(),
                'password' => 'Aa2302!@#' ,
                'password_confirmation' => 'Aa2302!@#',
                'avatar' => UploadedFile::fake()->image('image.png', 13 , 11),
                'role_id' => 1

            ]));

        $response->dd();
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'role_id',
                'role_name',
                'avatar'
            ]
        ]);
    }
}
