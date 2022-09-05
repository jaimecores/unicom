<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Seed the users
         \App\Models\User::factory(10)->create();

         //Seed the universities
         \App\Models\University::factory(50)->create()->each(

            function($university) {

                //Seed reviews for each university
                \App\Models\Review::factory(rand(5,20))->create(
                    ['university_id' => $university->id]
                );

                //Set the reviews count and rating for each university
                $university->reviews_count = $university->reviews->count();
                $university->rating = $university->reviews->pluck('rating')->avg();
                $university->save();

                //Seed favourite universities for some users  
                $users = \App\Models\User::inRandomOrder()->limit(rand(1,5))->get();
                foreach($users as $user) {
                    \App\Models\Favourite::create([
                        'university_id' => $university->id,
                        'user_id' => $user->id
                    ]);
                }
            }

        );
    }
}
