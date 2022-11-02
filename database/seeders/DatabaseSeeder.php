<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Jorge',
            'surname' => 'Montejo',
            'email' => 'jorgemon.lopez@gmail.com',
            'phone' => 123456789,
            'google' => false,
            'active' => true,
            'password' => bcrypt('password')
        ]);

        // //create 20 unique tags
        \App\Models\Tag::factory(20)->create();

        //create 10 users with 10 ads each
        \App\Models\User::factory(10)->has(\App\Models\Ad::factory(10))->create();

        //populate ads_tags table
        $ads = \App\Models\Ad::all();
        $tags = \App\Models\Tag::all();
        foreach ($ads as $ad) {
          $ad->tags()->attach($tags->random(1)->pluck('id')->toArray(), ["id" => Uuid::uuid4()]);
          $ad->tags()->attach($tags->random(rand(0, 1))->pluck('id')->toArray(), ["id" => Uuid::uuid4()]);
          $ad->tags()->attach($tags->random(rand(0, 1))->pluck('id')->toArray(), ["id" => Uuid::uuid4()]);
        }

    }
}
