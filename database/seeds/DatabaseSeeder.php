<?php

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
        // $this->call(UsersTableSeeder::class);
        //factory(App\Message::class, 20)
        /*factory(App\User::class, 50)->create()->each(function(App\User $user){
            factory(App\Message::class)
            ->times(20)
            ->create([
                'user_id' => $user->id
            ]);
        });*/
        $users = factory(App\User::class, 50)->create();

        $users->each(function(App\User $user) use ($users) {
            factory(App\Message::class)
                ->times(20)
                ->create([
                    'user_id' => $user->id
                ]);

            $user->follows()->sync(
                $users->random(10)
            );
            
        });
    }
}
