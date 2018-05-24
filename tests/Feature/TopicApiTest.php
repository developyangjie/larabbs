<?php

namespace Tests\Feature;

use App\Models\Topic;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\ActingJWTUser;

class TopicApiTest extends TestCase
{
    use ActingJWTUser;
    /**
     * A basic test example.
     *
     * @return void
     */

    protected $user;

    public function setUp(){
        parent::setUp();
        $this->user = factory(User::class)->create();
    }
    public function testStoreTopic()
    {
        $data = ['category_id' => 1, 'body' => 'test body', 'title' => 'test title'];

        $token = \Auth::guard('api')->fromUser($this->user);
        $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('POST', '/api/topics', $data);

        $assertData = [
            'category_id' => 1,
            'user_id' => $this->user->id,
            'title' => 'test title',
            'body' => clean('test body'),
        ];

        $response->assertStatus(200)
            ->assertJsonFragment($assertData);
    }

    public function testUpdateTopic(){
        $topic = $this->makeTopic();
        $editData = [
            "category_id"=>2,
            "body"=>"edit body",
            "title"=>"edit title"
        ];

        $response = $this->JWTActingAs($this->user)->json("PATCH","/api/topics/".$topic->id,$editData);

        $assetData = [
            "category_id"=>2,
            "user_id"=>$this->user->id,
            "title"=>"edit title",
            "body"=>clean("edit body")
        ];

        $response->assertStatus(200)->assertJsonFragment($assetData);
    }

    public function makeTopic(){
        return factory(Topic::class)->create(
            [
                "user_id"=>$this->user->id,
                "category_id"=>1
            ]
        );
    }
}
