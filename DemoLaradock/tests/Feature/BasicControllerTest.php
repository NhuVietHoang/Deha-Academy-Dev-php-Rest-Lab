<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Faker\Generator as Faker;
use Tests\TestCase;

class BasicControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_return_user_list()
    {
        $response = $this->get(route('users.index'));
        $response->assertStatus(200) 
            ->assertJsonStructure([
                'data',
                'messages',
            ]);
    }
    
    public function test_store_user_success()
    {
        $faker = \Faker\Factory::create();
        $userData = [
            'name' => $faker->name(),
            'email' => $faker->email(),
            'phone'=> $faker->numerify('##########'),
            'password' => bcrypt(123456789), 
        ];
        $response = $this->post('api/users', $userData); 
        $response->assertStatus(201)
            ->assertJson([ 
                'data' => [],
                'messages' => 'create success',
            ]);
    }

    public function test_show_user()
    {
        $response = $this->get('/api/users/5');
        $response->assertStatus(200);
        $response->assertJson([
            "data" => [],
            'messages' => 'success'
        ]);

    }

    public function test_update_user()
    {
        $faker = \Faker\Factory::create();
        $user =  User::factory()->create();
        $newUserData = [
            'name' => $faker->name(),
            'email' => $faker->email(),
            'phone' => $faker->numerify('##########'),
        ];
        $response = $this->put("api/users/{$user->id}", $newUserData);
        $response->assertStatus(200)->assertJson([
            "message"=> "update success",
                "code"=> 200,
                "data"=> $newUserData,
         ]);
    }

    public function test_update_user_false()
    {
        $faker = \Faker\Factory::create();
        $user =  User::factory()->create();
        $newUserData = [
            'name' => $faker->name(),
            'email' => $faker->email(),
            'phone' => $faker->numerify('##########'),
        ];
        $response = $this->put("api/users/abc1234", $newUserData);
        $response->assertStatus(500)->assertJson([
            "message"=> "update failed: No query results for model [App\\Models\\User] abc1234",
         ]);
    }

    public function test_delete_user()
    {
        $faker = \Faker\Factory::create();
        $user =  User::factory()->create();
        $response = $this->delete("api/users/{$user->id}");
        $response->assertStatus(200)->assertJson([
            "message" => "Xóa thành công",
            "code" => 200,
        ], 200);
    }
}
