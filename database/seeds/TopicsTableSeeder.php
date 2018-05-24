<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        $user_ids = \App\Models\User::all()->pluck("id")->toArray();
        $category_ids = \App\Models\Category::all()->pluck("id")->toArray();

        $faker = app(Faker\Generator::class);
        $topics = factory(Topic::class)->times(50)->make()->each(function ($topic, $index) use ($user_ids,$category_ids,$faker) {
            $topic->user_id = $user_ids[array_rand($user_ids,1)];
            $topic->category_id = $category_ids[array_rand($category_ids,1)];
        });

        Topic::insert($topics->toArray());
    }

}

